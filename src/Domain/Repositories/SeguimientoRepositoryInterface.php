<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Seguimiento;

/**
 * Interface SeguimientoRepositoryInterface
 */
interface SeguimientoRepositoryInterface
{
    public function save(Seguimiento $seguimiento): bool;
    public function findById(int $id): ?Seguimiento;
    public function findByEstudiante(int $idEstudiante): array;
    public function findAll(int $limit = 100): array;
    public function update(Seguimiento $seguimiento): bool;
    public function getAlertasAcademicas(): array;
    public function getAlertasVulnerabilidad(): array;
    public function obtenerEstadisticas(): array;
}
