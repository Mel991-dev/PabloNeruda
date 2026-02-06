<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Nota;

interface NotaRepositoryInterface
{
    public function findByMatricula(int $matriculaId, int $periodo): array;
    public function findByEstudianteAndPeriodo(int $estudianteId, int $periodo): array;
    public function findByCursoAndMateria(int $cursoId, int $materiaId, int $periodo): array;
    public function save(Nota $nota): int;
    public function update(Nota $nota): bool;
}
