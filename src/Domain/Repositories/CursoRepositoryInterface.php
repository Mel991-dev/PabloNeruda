<?php

namespace App\Domain\Repositories;

interface CursoRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?array; // Retornamos array por ahora para entidad simple
}
