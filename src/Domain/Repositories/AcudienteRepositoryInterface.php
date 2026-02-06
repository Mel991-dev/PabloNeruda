<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Acudiente;

interface AcudienteRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?Acudiente;
    public function findByDocumento(string $numeroDocumento): ?Acudiente;
    public function save(Acudiente $acudiente): int; // Retorna el ID creado
    public function update(Acudiente $acudiente): bool;
    public function delete(int $id): bool;
    public function search(string $term): array; // Buscar por nombre o documento
}
