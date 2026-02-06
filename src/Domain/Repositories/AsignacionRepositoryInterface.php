<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\AsignacionAcademica;

interface AsignacionRepositoryInterface
{
    public function findByCurso(int $idCurso): array;
    public function findByProfesor(int $idProfesor): array;
    public function save(AsignacionAcademica $asignacion): int;
    public function delete(int $id): bool;
    public function deleteByCurso(int $idCurso): bool;
}
