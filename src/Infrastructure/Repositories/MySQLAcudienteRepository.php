<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Acudiente;
use App\Domain\Repositories\AcudienteRepositoryInterface;
use App\Core\Database;
use PDO;

class MySQLAcudienteRepository implements AcudienteRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM acudientes ORDER BY apellido, nombre");
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = Acudiente::fromArray($row);
        }
        return $result;
    }

    public function findById(int $id): ?Acudiente
    {
        $stmt = $this->db->prepare("SELECT * FROM acudientes WHERE id_acudiente = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row ? Acudiente::fromArray($row) : null;
    }

    public function findByDocumento(string $numeroDocumento): ?Acudiente
    {
        $stmt = $this->db->prepare("SELECT * FROM acudientes WHERE numero_documento = :doc");
        $stmt->execute(['doc' => $numeroDocumento]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $row ? Acudiente::fromArray($row) : null;
    }

    public function save(Acudiente $acudiente): int
    {
        $sql = "INSERT INTO acudientes (
                    nombre, apellido, tipo_documento, numero_documento, telefono,
                    telefono_secundario, email, direccion, ocupacion
                ) VALUES (
                    :nombre, :apellido, :tipo_documento, :numero_documento, :telefono,
                    :telefono_secundario, :email, :direccion, :ocupacion
                )";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nombre' => $acudiente->getNombre(),
            'apellido' => $acudiente->getApellido(),
            'tipo_documento' => $acudiente->getTipoDocumento(),
            'numero_documento' => $acudiente->getNumeroDocumento(),
            'telefono' => $acudiente->getTelefono(),
            'telefono_secundario' => $acudiente->getTelefonoSecundario(),
            'email' => $acudiente->getEmail(),
            'direccion' => $acudiente->getDireccion(),
            'ocupacion' => $acudiente->getOcupacion()
        ]);
        
        return (int) $this->db->lastInsertId();
    }

    public function update(Acudiente $acudiente): bool
    {
        $sql = "UPDATE acudientes SET 
                    nombre = :nombre,
                    apellido = :apellido,
                    tipo_documento = :tipo_documento,
                    numero_documento = :numero_documento,
                    telefono = :telefono,
                    telefono_secundario = :telefono_secundario,
                    email = :email,
                    direccion = :direccion,
                    ocupacion = :ocupacion
                WHERE id_acudiente = :id";
                
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nombre' => $acudiente->getNombre(),
            'apellido' => $acudiente->getApellido(),
            'tipo_documento' => $acudiente->getTipoDocumento(),
            'numero_documento' => $acudiente->getNumeroDocumento(),
            'telefono' => $acudiente->getTelefono(),
            'telefono_secundario' => $acudiente->getTelefonoSecundario(),
            'email' => $acudiente->getEmail(),
            'direccion' => $acudiente->getDireccion(),
            'ocupacion' => $acudiente->getOcupacion(),
            'id' => $acudiente->getIdAcudiente()
        ]);
    }

    public function delete(int $id): bool
    {
        // En acudientes usamos borrado físico si no tiene dependencias, o manejamos excepción
        try {
            $stmt = $this->db->prepare("DELETE FROM acudientes WHERE id_acudiente = :id");
            return $stmt->execute(['id' => $id]);
        } catch (\PDOException $e) {
            // Si hay restricción de clave foránea, no eliminamos
            return false;
        }
    }

    public function search(string $term): array
    {
        $term = "%$term%";
        $stmt = $this->db->prepare("SELECT * FROM acudientes WHERE nombre LIKE :term OR apellido LIKE :term OR numero_documento LIKE :term");
        $stmt->execute(['term' => $term]);
        
        $result = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $result[] = Acudiente::fromArray($row);
        }
        return $result;
    }
}
