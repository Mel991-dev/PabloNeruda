<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\MateriaRepositoryInterface;
use App\Domain\Entities\Materia;
use App\Core\Database;
use PDO;

class MySQLMateriaRepository implements MateriaRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM materias ORDER BY nombre");
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = Materia::fromArray($row);
        }
        return $results;
    }

    public function findById(int $id): ?Materia
    {
        $stmt = $this->db->prepare("SELECT * FROM materias WHERE id_materia = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? Materia::fromArray($row) : null;
    }
}
