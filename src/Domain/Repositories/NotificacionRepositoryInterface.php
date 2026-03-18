<?php

namespace App\Domain\Repositories;

use App\Domain\Entities\Notificacion;

interface NotificacionRepositoryInterface
{
    public function save(Notificacion $notificacion): bool;
    public function findUnreadByDestino(?int $usuarioId, ?string $rolDestino): array;
    public function markAsRead(int $notificacionId): bool;
    public function markAllAsRead(?int $usuarioId, ?string $rolDestino): bool;
    public function countUnread(?int $usuarioId, ?string $rolDestino): int;
}
