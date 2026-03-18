<?php

namespace App\Domain\Services;

use App\Domain\Entities\Nota;
use App\Domain\Repositories\NotaRepositoryInterface;
use App\Domain\Repositories\CursoRepositoryInterface;
use App\Domain\Repositories\MateriaRepositoryInterface;
use App\Core\Database;
use App\Core\Session;

class NotaService
{
    private NotaRepositoryInterface $notaRepo;
    private CursoRepositoryInterface $cursoRepo;
    private MateriaRepositoryInterface $materiaRepo;

    public function __construct(
        NotaRepositoryInterface $notaRepo,
        CursoRepositoryInterface $cursoRepo,
        MateriaRepositoryInterface $materiaRepo
    ) {
        $this->notaRepo = $notaRepo;
        $this->cursoRepo = $cursoRepo;
        $this->materiaRepo = $materiaRepo;
    }

    public function listarCursos(?int $fkProfesor = null): array
    {
        if ($fkProfesor) {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("
                SELECT DISTINCT c.* 
                FROM cursos c 
                JOIN horarios h ON c.id_curso = h.fk_curso 
                WHERE h.fk_profesor = ?
                ORDER BY c.grado, c.seccion
            ");
            $stmt->execute([$fkProfesor]);
            return $stmt->fetchAll();
        }
        return $this->cursoRepo->findAll();
    }

    public function listarMaterias(?int $fkProfesor = null, ?int $cursoId = null): array
    {
        if ($fkProfesor && $cursoId) {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("
                SELECT DISTINCT m.* 
                FROM materias m 
                JOIN horarios h ON m.id_materia = h.fk_materia 
                WHERE h.fk_profesor = ? AND h.fk_curso = ?
                ORDER BY m.nombre
            ");
            $stmt->execute([$fkProfesor, $cursoId]);
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $results = [];
            foreach ($rows as $row) {
                $results[] = \App\Domain\Entities\Materia::fromArray($row);
            }
            return $results;
        } elseif ($fkProfesor) {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("
                SELECT DISTINCT m.* 
                FROM materias m 
                JOIN horarios h ON m.id_materia = h.fk_materia 
                WHERE h.fk_profesor = ?
                ORDER BY m.nombre
            ");
            $stmt->execute([$fkProfesor]);
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $results = [];
            foreach ($rows as $row) {
                $results[] = \App\Domain\Entities\Materia::fromArray($row);
            }
            return $results;
        }
        return $this->materiaRepo->findAll();
    }
    
    public function obtenerNombreMateria(int $id): string
    {
        $materia = $this->materiaRepo->findById($id);
        return $materia ? $materia->getNombre() : 'Desconocida';
    }
    
    public function obtenerNombreCurso(int $id): string
    {
        $curso = $this->cursoRepo->findById($id);
        return $curso ? ($curso['grado'] . ' - ' . $curso['seccion']) : 'Desconocido';
    }

    public function obtenerEstudiantesConNotas(int $cursoId, int $materiaId, int $periodo): array
    {
        return $this->notaRepo->findByCursoAndMateria($cursoId, $materiaId, $periodo);
    }

    public function registrarNotas(int $materiaId, int $periodo, array $notasEstudiantes, int $fkProfesor): void
    {
        $db = Database::getInstance()->getConnection();
        
        try {
            $db->beginTransaction();

            // Validación de seguridad (Notas Huérfanas)
            // Si fkProfesor es válido (> 1 ya que 1 es admin fallback, o aplicamos strict check)
            if ($fkProfesor && Session::get('rol') === 'Profesor') {
                $checkStmt = $db->prepare("
                    SELECT 1 FROM horarios 
                    WHERE fk_curso = (SELECT fk_curso FROM matriculas WHERE id_matricula = ?)
                    AND fk_materia = ? AND fk_profesor = ? LIMIT 1
                ");
            }

            foreach ($notasEstudiantes as $matriculaId => $notas) {
                // Verificar permisos del profesor para este estudiante/materia si aplica
                if (isset($checkStmt)) {
                    $checkStmt->execute([$matriculaId, $materiaId, $fkProfesor]);
                    if (!$checkStmt->fetchColumn()) {
                        throw new \Exception("Seguridad: No tienes asignada esta materia en el curso de este estudiante.");
                    }
                }

                // Crear objeto nota
                $nota = new Nota();
                $nota->setFkMatricula($matriculaId);
                $nota->setFkMateria($materiaId);
                $nota->setFkProfesor($fkProfesor); // Set the professor ID
                $nota->setPeriodo($periodo);
                
                // Establecer las 5 notas (validación y cálculo de promedio ocurren aquí)
                $nota->setNotas(
                    (float)($notas[1] ?? 0),
                    (float)($notas[2] ?? 0),
                    (float)($notas[3] ?? 0),
                    (float)($notas[4] ?? 0),
                    (float)($notas[5] ?? 0)
                );

                if (isset($notas['observaciones'])) {
                    $nota->setObservaciones($notas['observaciones']);
                }
                
                // Guardar (el repositorio maneja insert vs update)
                $this->notaRepo->save($nota);
            }

            $db->commit();

            // --- SISTEMA DE ALERTAS TEMPRANAS (MOTOR DE NOTIFICACIONES) ---
            try {
                $notificacionRepo = new \App\Infrastructure\Repositories\MySQLNotificacionRepository();
                $matriculasIds = implode(',', array_map('intval', array_keys($notasEstudiantes)));
                
                if (!empty($matriculasIds)) {
                    $sqlAlerta = "SELECT v.*, e.nombre, e.apellido 
                                  FROM v_estudiantes_riesgo_academico v
                                  JOIN estudiantes e ON v.id_estudiante = e.id_estudiante
                                  WHERE v.id_estudiante IN (
                                      SELECT fk_estudiante FROM matriculas WHERE id_matricula IN ($matriculasIds)
                                  )";
                    $estudiantesEnRiesgo = $db->query($sqlAlerta)->fetchAll(\PDO::FETCH_ASSOC);
                    
                    foreach ($estudiantesEnRiesgo as $riesgo) {
                        if ((int)$riesgo['materias_perdidas'] >= 3 || (float)$riesgo['promedio_general'] < 3.0) {
                            $materiaObj = $this->materiaRepo->findById($materiaId);
                            $nombreMat = $materiaObj ? $materiaObj->getNombre() : 'Materia';
                            $nombreEst = $riesgo['nombre'] . ' ' . $riesgo['apellido'];
                            
                            // Check for spam: Prevent multiple unread alerts for the same student
                            $spamCheck = $db->prepare("SELECT 1 FROM notificaciones WHERE rol_destino = 'Orientador' AND leida = 0 AND mensaje LIKE ?");
                            $spamCheck->execute(["%{$nombreEst}%"]);
                            if (!$spamCheck->fetchColumn()) {
                                $notificacion = new \App\Domain\Entities\Notificacion(
                                    'Riesgo Académico',
                                    "Alerta: $nombreEst",
                                    "El estudiante está fallando en $nombreMat. Tiene {$riesgo['materias_perdidas']} materias reprobadas (Promedio: {$riesgo['promedio_general']}).",
                                    'Orientador',
                                    $fkProfesor, /* Origen */
                                    null, /* A todos los orientadores */
                                    "/orientacion/nuevo?id_estudiante=" . $riesgo['id_estudiante']
                                );
                                $notificacionRepo->save($notificacion);
                            }
                        }
                    }
                }
            } catch (\Exception $eAlerta) {
                error_log("Error Notification Engine: " . $eAlerta->getMessage());
            }
            // --- FIN SISTEMA DE ALERTAS ---

        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
