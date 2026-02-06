<?php

namespace App\Infrastructure\Repositories;

use App\Core\Database;
use PDO;

class MySQLProfesorRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM profesores WHERE estado = 'Activo' ORDER BY apellido, nombre";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM profesores WHERE id_profesor = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function save(array $data): int
    {
        $sql = "INSERT INTO profesores (nombre, apellido, tipo_documento, numero_documento, telefono, email, especialidad, estado, fecha_ingreso) 
                VALUES (:nombre, :apellido, :tipo_documento, :numero_documento, :telefono, :email, :especialidad, 'Activo', CURRENT_DATE)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'tipo_documento' => $data['tipo_documento'],
            'numero_documento' => $data['numero_documento'],
            'telefono' => $data['telefono'] ?? null,
            'email' => $data['email'] ?? null,
            'especialidad' => $data['especialidad'] ?? null
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function update(array $data): void
    {
        $sql = "UPDATE profesores SET 
                nombre = :nombre,
                apellido = :apellido,
                tipo_documento = :tipo_documento,
                numero_documento = :numero_documento,
                telefono = :telefono,
                email = :email,
                especialidad = :especialidad
                WHERE id_profesor = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'],
            'tipo_documento' => $data['tipo_documento'],
            'numero_documento' => $data['numero_documento'],
            'telefono' => $data['telefono'] ?? null,
            'email' => $data['email'] ?? null,
            'especialidad' => $data['especialidad'] ?? null,
            'id' => $data['id_profesor']
        ]);
    }
}
