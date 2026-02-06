<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Materia;

interface MateriaRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?Materia;
}
