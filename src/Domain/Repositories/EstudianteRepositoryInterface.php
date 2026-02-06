<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Estudiante;

interface EstudianteRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?Estudiante;
    public function findByCurso(int $cursoId): array;
    public function findByDocumento(string $numeroDocumento): ?Estudiante;
    public function save(Estudiante $estudiante): int; // Retorna el ID creado
    public function update(Estudiante $estudiante): bool;
    public function delete(int $id): bool;
    public function countByCurso(int $cursoId): int;
    
    // Métodos para acudientes relacionados
    public function getAcudientes(int $estudianteId): array;
    public function addAcudiente(int $estudianteId, int $acudienteId, string $parentesco, bool $principal, bool $viveCon, bool $autorizado);
    public function removeAcudientes(int $estudianteId): bool;
}
