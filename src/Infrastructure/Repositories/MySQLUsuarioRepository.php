<?php

namespace App\Infrastructure\Repositories;

use App\Core\Database;
use App\Domain\Entities\Usuario;
use App\Domain\Repositories\UsuarioRepositoryInterface;
use PDO;

/**
 * Implementación MySQL del repositorio de usuarios
 */
class MySQLUsuarioRepository implements UsuarioRepositoryInterface
{
    private PDO $connection;

    public function __construct()
    {
        $this->connection = Database::getInstance()->getConnection();
    }

    public function findByUsername(string $username): ?Usuario
    {
        $sql = "SELECT * FROM usuarios WHERE username = :username LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['username' => $username]);
        
        $data = $stmt->fetch();
        
        return $data ? Usuario::fromArray($data) : null;
    }

    public function findById(int $id): ?Usuario
    {
        $sql = "SELECT * FROM usuarios WHERE id_usuario = :id LIMIT 1";
        $stmt = $this->connection->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        $data = $stmt->fetch();
        
        return $data ? Usuario::fromArray($data) : null;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM usuarios ORDER BY id_usuario DESC";
        $stmt = $this->connection->query($sql);
        
        $usuarios = [];
        while ($data = $stmt->fetch()) {
            $usuarios[] = Usuario::fromArray($data);
        }
        
        return $usuarios;
    }

    public function save(Usuario $usuario): int
    {
        $sql = "INSERT INTO usuarios (username, password_hash, rol, fk_profesor, estado, fecha_creacion) 
                VALUES (:username, :password_hash, :rol, :fk_profesor, :estado, NOW())";
        
        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'username' => $usuario->getUsername(),
            'password_hash' => $usuario->getPasswordHash(),
            'rol' => $usuario->getRol(),
            'fk_profesor' => $usuario->getFkProfesor(),
            'estado' => $usuario->getEstado()
        ]);
        
        return (int) $this->connection->lastInsertId();
    }

    public function update(Usuario $usuario): bool
    {
        $sql = "UPDATE usuarios SET 
                username = :username,
                password_hash = :password_hash,
                rol = :rol,
                fk_profesor = :fk_profesor,
                estado = :estado
                WHERE id_usuario = :id";
        
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([
            'id' => $usuario->getIdUsuario(),
            'username' => $usuario->getUsername(),
            'password_hash' => $usuario->getPasswordHash(),
            'rol' => $usuario->getRol(),
            'fk_profesor' => $usuario->getFkProfesor(),
            'estado' => $usuario->getEstado()
        ]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM usuarios WHERE id_usuario = :id";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function updateLastAccess(int $id): bool
    {
        $sql = "UPDATE usuarios SET ultimo_acceso = NOW() WHERE id_usuario = :id";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
