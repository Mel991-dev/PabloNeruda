<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Matricula;
use App\Domain\Repositories\MatriculaRepositoryInterface;
use App\Core\Database;
use PDO;

class MySQLMatriculaRepository implements MatriculaRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById(int $id): ?Matricula
    {
        $stmt = $this->db->prepare("SELECT * FROM matriculas WHERE id_matricula = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? Matricula::fromArray($row) : null;
    }

    public function findActiveByEstudiante(int $idEstudiante): ?Matricula
    {
        $stmt = $this->db->prepare("SELECT * FROM matriculas WHERE fk_estudiante = :id AND estado = 'Activo' LIMIT 1");
        $stmt->execute(['id' => $idEstudiante]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? Matricula::fromArray($row) : null;
    }

    public function findHistoryByEstudiante(int $idEstudiante): array
    {
        $stmt = $this->db->prepare("SELECT * FROM matriculas WHERE fk_estudiante = :id ORDER BY año_lectivo DESC");
        $stmt->execute(['id' => $idEstudiante]);
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = Matricula::fromArray($row);
        }
        return $results;
    }

    public function save(Matricula $matricula): int
    {
        $sql = "INSERT INTO matriculas (fk_estudiante, fk_curso, año_lectivo, fecha_matricula, estado) 
                VALUES (:fk_estudiante, :fk_curso, :anio, :fecha, :estado)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'fk_estudiante' => $matricula->getFkEstudiante(),
            'fk_curso' => $matricula->getFkCurso(),
            'anio' => $matricula->getAñoLectivo(),
            'fecha' => $matricula->getFechaMatricula(),
            'estado' => $matricula->getEstado()
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(Matricula $matricula): bool
    {
        $sql = "UPDATE matriculas SET 
                fk_estudiante = :fk_estudiante,
                fk_curso = :fk_curso,
                año_lectivo = :anio,
                fecha_matricula = :fecha,
                estado = :estado
                WHERE id_matricula = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $matricula->getIdMatricula(),
            'fk_estudiante' => $matricula->getFkEstudiante(),
            'fk_curso' => $matricula->getFkCurso(),
            'anio' => $matricula->getAñoLectivo(),
            'fecha' => $matricula->getFechaMatricula(),
            'estado' => $matricula->getEstado()
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM matriculas WHERE id_matricula = :id");
        return $stmt->execute(['id' => $id]);
    }
}
