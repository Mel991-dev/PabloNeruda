<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Usuario;

/**
 * Interfaz UsuarioRepositoryInterface
 * Define el contrato para el repositorio de usuarios
 */
interface UsuarioRepositoryInterface
{
    /**
     * Buscar usuario por username
     */
    public function findByUsername(string $username): ?Usuario;

    /**
     * Buscar usuario por ID
     */
    public function findById(int $id): ?Usuario;

    /**
     * Obtener todos los usuarios
     */
    public function findAll(): array;

    /**
     * Guardar nuevo usuario
     */
    public function save(Usuario $usuario): int;

    /**
     * Actualizar usuario existente
     */
    public function update(Usuario $usuario): bool;

    /**
     * Eliminar usuario
     */
    public function delete(int $id): bool;

    /**
     * Actualizar último acceso
     */
    public function updateLastAccess(int $id): bool;
}
