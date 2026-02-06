<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Matricula;

interface MatriculaRepositoryInterface
{
    public function findById(int $id): ?Matricula;
    public function findActiveByEstudiante(int $idEstudiante): ?Matricula;
    public function findHistoryByEstudiante(int $idEstudiante): array;
    public function save(Matricula $matricula): int;
    public function update(Matricula $matricula): bool;
    public function delete(int $id): bool;
}
