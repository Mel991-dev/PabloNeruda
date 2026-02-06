<?php

namespace App\Domain\Entities;

/**
 * Entidad Usuario
 */
class Usuario
{
    private ?int $id_usuario = null;
    private string $username;
    private string $password_hash;
    private string $rol;
    private ?int $fk_profesor = null;
    private string $estado = 'Activo';
    private ?string $fecha_creacion = null;
    private ?string $ultimo_acceso = null;

    // Getters
    public function getIdUsuario(): ?int
    {
        return $this->id_usuario;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }

    public function getRol(): string
    {
        return $this->rol;
    }

    public function getFkProfesor(): ?int
    {
        return $this->fk_profesor;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function getFechaCreacion(): ?string
    {
        return $this->fecha_creacion;
    }

    public function getUltimoAcceso(): ?string
    {
        return $this->ultimo_acceso;
    }

    // Setters
    public function setIdUsuario(?int $id_usuario): void
    {
        $this->id_usuario = $id_usuario;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function setPasswordHash(string $password_hash): void
    {
        $this->password_hash = $password_hash;
    }

    public function setRol(string $rol): void
    {
        $rolesValidos = ['Administrador', 'Rector', 'Coordinador', 'Profesor', 'Orientador'];
        if (!in_array($rol, $rolesValidos)) {
            throw new \InvalidArgumentException("Rol inválido: $rol");
        }
        $this->rol = $rol;
    }

    public function setFkProfesor(?int $fk_profesor): void
    {
        $this->fk_profesor = $fk_profesor;
    }

    public function setEstado(string $estado): void
    {
        $estadosValidos = ['Activo', 'Inactivo'];
        if (!in_array($estado, $estadosValidos)) {
            throw new \InvalidArgumentException("Estado inválido: $estado");
        }
        $this->estado = $estado;
    }

    public function setFechaCreacion(?string $fecha_creacion): void
    {
        $this->fecha_creacion = $fecha_creacion;
    }

    public function setUltimoAcceso(?string $ultimo_acceso): void
    {
        $this->ultimo_acceso = $ultimo_acceso;
    }

    /**
     * Verificar si el usuario está activo
     */
    public function isActivo(): bool
    {
        return $this->estado === 'Activo';
    }

    /**
     * Crear desde array
     */
    public static function fromArray(array $data): self
    {
        $usuario = new self();
        
        if (isset($data['id_usuario'])) $usuario->setIdUsuario($data['id_usuario']);
        if (isset($data['username'])) $usuario->setUsername($data['username']);
        if (isset($data['password_hash'])) $usuario->setPasswordHash($data['password_hash']);
        if (isset($data['rol'])) $usuario->setRol($data['rol']);
        if (isset($data['fk_profesor'])) $usuario->setFkProfesor($data['fk_profesor']);
        if (isset($data['estado'])) $usuario->setEstado($data['estado']);
        if (isset($data['fecha_creacion'])) $usuario->setFechaCreacion($data['fecha_creacion']);
        if (isset($data['ultimo_acceso'])) $usuario->setUltimoAcceso($data['ultimo_acceso']);
        
        return $usuario;
    }

    /**
     * Convertir a array
     */
    public function toArray(): array
    {
        return [
            'id_usuario' => $this->id_usuario,
            'username' => $this->username,
            'rol' => $this->rol,
            'fk_profesor' => $this->fk_profesor,
            'estado' => $this->estado,
            'fecha_creacion' => $this->fecha_creacion,
            'ultimo_acceso' => $this->ultimo_acceso
        ];
    }
}
