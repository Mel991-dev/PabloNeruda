<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\AsignacionAcademica;
use App\Domain\Repositories\AsignacionRepositoryInterface;
use App\Core\Database;
use PDO;

class MySQLAsignacionRepository implements AsignacionRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByCurso(int $idCurso): array
    {
        $sql = "SELECT * FROM profesor_materia_curso WHERE fk_curso = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idCurso]);
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = AsignacionAcademica::fromArray($row);
        }
        return $results;
    }

    public function findByProfesor(int $idProfesor): array
    {
        $sql = "SELECT * FROM profesor_materia_curso WHERE fk_profesor = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idProfesor]);
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = AsignacionAcademica::fromArray($row);
        }
        return $results;
    }

    public function save(AsignacionAcademica $asignacion): int
    {
        $sql = "INSERT INTO profesor_materia_curso (fk_profesor, fk_materia, fk_curso) 
                VALUES (:fk_profesor, :fk_materia, :fk_curso)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'fk_profesor' => $asignacion->getFkProfesor(),
            'fk_materia' => $asignacion->getFkMateria(),
            'fk_curso' => $asignacion->getFkCurso()
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM profesor_materia_curso WHERE id_asignacion = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function deleteByCurso(int $idCurso): bool
    {
        $stmt = $this->db->prepare("DELETE FROM profesor_materia_curso WHERE fk_curso = :id");
        return $stmt->execute(['id' => $id]);
    }
}
