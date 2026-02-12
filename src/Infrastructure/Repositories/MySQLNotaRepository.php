<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Nota;
use App\Domain\Repositories\NotaRepositoryInterface;
use App\Core\Database;
use PDO;

class MySQLNotaRepository implements NotaRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByMatricula(int $matriculaId, int $periodo): array
    {
        $sql = "SELECT * FROM notas WHERE fk_matricula = :matricula_id AND periodo = :periodo";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['matricula_id' => $matriculaId, 'periodo' => $periodo]);
        
        $notas = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $notas[] = Nota::fromArray($row);
        }
        return $notas;
    }

    public function findByEstudianteAndPeriodo(int $estudianteId, int $periodo): array
    {
        // Modificación: Traer TODAS las materias del grado, con o sin notas
        $sql = "SELECT 
                    m.id_materia,
                    m.nombre as materia_nombre,
                    n.id_nota,
                    n.fk_matricula,
                    n.fk_profesor,
                    n.periodo,
                    n.nota_1 as nota1,
                    n.nota_2 as nota2,
                    n.nota_3 as nota3,
                    n.nota_4 as nota4,
                    n.nota_5 as nota5,
                    n.promedio_periodo as promedio,
                    n.estado,
                    n.observaciones,
                    n.fecha_registro
                FROM matriculas mat
                INNER JOIN cursos c ON mat.fk_curso = c.id_curso
                INNER JOIN materias m ON (m.grado_aplicable = c.grado OR m.grado_aplicable = 'Todos')
                LEFT JOIN notas n ON n.fk_materia = m.id_materia 
                                  AND n.fk_matricula = mat.id_matricula 
                                  AND n.periodo = :periodo
                WHERE mat.fk_estudiante = :est_id 
                AND mat.estado = 'Activo'
                ORDER BY m.nombre";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['est_id' => $estudianteId, 'periodo' => $periodo]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function findByCursoAndMateria(int $cursoId, int $materiaId, int $periodo): array
    {
        // Obtener estudiantes del curso y sus notas para esta materia/periodo
        $sql = "SELECT e.id_estudiante, e.nombre, e.apellido, e.numero_documento,
                       mat.id_matricula,
                       n.id_nota, 
                       n.nota_1 as nota1, 
                       n.nota_2 as nota2, 
                       n.nota_3 as nota3, 
                       n.nota_4 as nota4, 
                       n.nota_5 as nota5, 
                       n.promedio_periodo as promedio, 
                       n.estado
                FROM estudiantes e
                INNER JOIN matriculas mat ON e.id_estudiante = mat.fk_estudiante
                LEFT JOIN notas n ON mat.id_matricula = n.fk_matricula AND n.fk_materia = :materia_id AND n.periodo = :periodo
                WHERE mat.fk_curso = :curso_id 
                AND mat.estado = 'Activo' 
                AND e.estado = 'Activo'
                ORDER BY e.apellido, e.nombre";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'curso_id' => $cursoId,
            'materia_id' => $materiaId,
            'periodo' => $periodo
        ]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save(Nota $nota): int
    {
        // Verificar si ya existe nota para esa matricula/materia/periodo
        $sqlCheck = "SELECT id_nota FROM notas WHERE fk_matricula = :mat AND fk_materia = :mat_id AND periodo = :per";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->execute([
            'mat' => $nota->getFkMatricula(),
            'mat_id' => $nota->getFkMateria(),
            'per' => $nota->getPeriodo()
        ]);
        
        if ($row = $stmtCheck->fetch(PDO::FETCH_ASSOC)) {
            // Si existe, actualizamos
            $nota->setIdNota($row['id_nota']);
            $this->update($nota);
            return $row['id_nota'];
        }

        // INSERT sin columnas generadas (promedio_periodo, estado)
        $sql = "INSERT INTO notas (
                    fk_matricula, fk_materia, fk_profesor, periodo, 
                    nota_1, nota_2, nota_3, nota_4, nota_5, 
                    observaciones, fecha_registro
                ) VALUES (
                    :fk_matricula, :fk_materia, :fk_profesor, :periodo, 
                    :nota1, :nota2, :nota3, :nota4, :nota5, 
                    :observaciones, NOW()
                )";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'fk_matricula' => $nota->getFkMatricula(),
            'fk_materia' => $nota->getFkMateria(),
            'fk_profesor' => $nota->getFkProfesor(),
            'periodo' => $nota->getPeriodo(),
            'nota1' => $nota->getNota1(),
            'nota2' => $nota->getNota2(),
            'nota3' => $nota->getNota3(),
            'nota4' => $nota->getNota4(),
            'nota5' => $nota->getNota5(),
            'observaciones' => $nota->getObservaciones()
        ]);
        
        return (int) $this->db->lastInsertId();
    }

    public function update(Nota $nota): bool
    {
        // UPDATE sin columnas generadas
        $sql = "UPDATE notas SET 
                    nota_1 = :nota1,
                    nota_2 = :nota2,
                    nota_3 = :nota3,
                    nota_4 = :nota4,
                    nota_5 = :nota5,
                    observaciones = :observaciones,
                    fecha_modificacion = NOW()
                WHERE id_nota = :id";
                
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nota1' => $nota->getNota1(),
            'nota2' => $nota->getNota2(),
            'nota3' => $nota->getNota3(),
            'nota4' => $nota->getNota4(),
            'nota5' => $nota->getNota5(),
            'observaciones' => $nota->getObservaciones(),
            'id' => $nota->getIdNota()
        ]);
    }
}
