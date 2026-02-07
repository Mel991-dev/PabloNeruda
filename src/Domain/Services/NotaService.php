<?php

namespace App\Domain\Services;

use App\Domain\Entities\Nota;
use App\Domain\Repositories\NotaRepositoryInterface;
use App\Domain\Repositories\CursoRepositoryInterface;
use App\Domain\Repositories\MateriaRepositoryInterface;
use App\Core\Database;

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

    public function listarCursos(): array
    {
        return $this->cursoRepo->findAll();
    }

    public function listarMaterias(): array
    {
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

            foreach ($notasEstudiantes as $matriculaId => $notas) {
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
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
