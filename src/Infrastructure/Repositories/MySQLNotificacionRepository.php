<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Notificacion;
use App\Domain\Repositories\NotificacionRepositoryInterface;
use App\Core\Database;
use PDO;

class MySQLNotificacionRepository implements NotificacionRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function save(Notificacion $notificacion): bool
    {
        $sql = "INSERT INTO notificaciones (
                    fk_usuario_origen, fk_usuario_destino, rol_destino, 
                    tipo, titulo, mensaje, enlace, leida
                ) VALUES (
                    :origen, :destino, :rol, :tipo, :titulo, :mensaje, :enlace, :leida
                )";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'origen' => $notificacion->getUsuarioOrigenId(),
            'destino' => $notificacion->getUsuarioDestinoId(),
            'rol' => $notificacion->getRolDestino(),
            'tipo' => $notificacion->getTipo(),
            'titulo' => $notificacion->getTitulo(),
            'mensaje' => $notificacion->getMensaje(),
            'enlace' => $notificacion->getEnlace(),
            'leida' => $notificacion->isLeida() ? 1 : 0
        ]);
    }

    public function findUnreadByDestino(?int $usuarioId, ?string $rolDestino): array
    {
        // Obtiene notificaciones dirigidas al ID específico O al rol específico
        $sql = "SELECT * FROM notificaciones 
                WHERE leida = 0 
                AND (
                    (fk_usuario_destino IS NOT NULL AND fk_usuario_destino = :usuarioId)
                    OR 
                    (rol_destino IS NOT NULL AND rol_destino = :rolDestino)
                )
                ORDER BY fecha_creacion DESC LIMIT 10";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'usuarioId' => $usuarioId,
            'rolDestino' => $rolDestino
        ]);
        
        $notificaciones = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $notificaciones[] = Notificacion::fromArray($row);
        }
        return $notificaciones;
    }

    public function markAsRead(int $notificacionId): bool
    {
        $sql = "UPDATE notificaciones SET leida = 1 WHERE id_notificacion = ?";
        return $this->db->prepare($sql)->execute([$notificacionId]);
    }

    public function markAllAsRead(?int $usuarioId, ?string $rolDestino): bool
    {
        $sql = "UPDATE notificaciones SET leida = 1 
                WHERE leida = 0 
                AND (
                    (fk_usuario_destino IS NOT NULL AND fk_usuario_destino = :usuarioId)
                    OR 
                    (rol_destino IS NOT NULL AND rol_destino = :rolDestino)
                )";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'usuarioId' => $usuarioId,
            'rolDestino' => $rolDestino
        ]);
    }

    public function countUnread(?int $usuarioId, ?string $rolDestino): int
    {
        $sql = "SELECT COUNT(*) FROM notificaciones 
                WHERE leida = 0 
                AND (
                    (fk_usuario_destino IS NOT NULL AND fk_usuario_destino = :usuarioId)
                    OR 
                    (rol_destino IS NOT NULL AND rol_destino = :rolDestino)
                )";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'usuarioId' => $usuarioId,
            'rolDestino' => $rolDestino
        ]);
        return (int)$stmt->fetchColumn();
    }
}
