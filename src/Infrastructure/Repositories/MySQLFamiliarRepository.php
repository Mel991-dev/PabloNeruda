<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Familiar;
use App\Domain\Repositories\FamiliarRepositoryInterface;
use App\Core\Database;
use PDO;

class MySQLFamiliarRepository implements FamiliarRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById(int $id): ?Familiar
    {
        $stmt = $this->db->prepare("SELECT * FROM familiares WHERE id_familiar = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? Familiar::fromArray($row) : null;
    }

    public function findByDocumento(string $numeroDocumento): ?Familiar
    {
        $stmt = $this->db->prepare("SELECT * FROM familiares WHERE numero_documento = :doc");
        $stmt->execute(['doc' => $numeroDocumento]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? Familiar::fromArray($row) : null;
    }

    public function save(Familiar $familiar): int
    {
        $sql = "INSERT INTO familiares (
            nombre, apellido, tipo_documento, numero_documento, ocupacion, empresa,
            nivel_educativo, telefono, direccion, barrio, email, vive_con_estudiante
        ) VALUES (
            :nombre, :apellido, :tipo_doc, :num_doc, :ocupacion, :empresa,
            :nivel, :telefono, :direccion, :barrio, :email, :vive_con
        )";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nombre' => $familiar->getNombre(),
            'apellido' => $familiar->getApellido(),
            'tipo_doc' => $familiar->getTipoDocumento(),
            'num_doc' => $familiar->getNumeroDocumento(),
            'ocupacion' => $familiar->getOcupacion(),
            'empresa' => $familiar->getEmpresa(),
            'nivel' => $familiar->getNivelEducativo(),
            'telefono' => $familiar->getTelefono(),
            'direccion' => $familiar->getDireccion(),
            'barrio' => $familiar->getBarrio(),
            'email' => $familiar->getEmail(),
            'vive_con' => $familiar->viveConEstudiante() ? 1 : 0
        ]);
        
        return (int) $this->db->lastInsertId();
    }

    public function update(Familiar $familiar): bool
    {
        $sql = "UPDATE familiares SET 
            nombre = :nombre,
            apellido = :apellido,
            tipo_documento = :tipo_doc,
            numero_documento = :num_doc,
            ocupacion = :ocupacion,
            empresa = :empresa,
            nivel_educativo = :nivel,
            telefono = :telefono,
            direccion = :direccion,
            barrio = :barrio,
            email = :email,
            vive_con_estudiante = :vive_con
            WHERE id_familiar = :id";
            
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nombre' => $familiar->getNombre(),
            'apellido' => $familiar->getApellido(),
            'tipo_doc' => $familiar->getTipoDocumento(),
            'num_doc' => $familiar->getNumeroDocumento(),
            'ocupacion' => $familiar->getOcupacion(),
            'empresa' => $familiar->getEmpresa(),
            'nivel' => $familiar->getNivelEducativo(),
            'telefono' => $familiar->getTelefono(),
            'direccion' => $familiar->getDireccion(),
            'barrio' => $familiar->getBarrio(),
            'email' => $familiar->getEmail(),
            'vive_con' => $familiar->viveConEstudiante() ? 1 : 0,
            'id' => $familiar->getId()
        ]);
    }
}
