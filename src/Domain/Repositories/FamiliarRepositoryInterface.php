<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Familiar;

interface FamiliarRepositoryInterface
{
    public function findById(int $id): ?Familiar;
    public function findByDocumento(string $numeroDocumento): ?Familiar;
    public function save(Familiar $familiar): int;
    public function update(Familiar $familiar): bool;
}
