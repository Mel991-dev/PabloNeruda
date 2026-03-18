<?php

namespace App\Application\Controllers;

use App\Core\{Response, Session};
use App\Infrastructure\Repositories\MySQLNotificacionRepository;

class NotificacionController
{
    private MySQLNotificacionRepository $repo;

    public function __construct()
    {
        $this->repo = new MySQLNotificacionRepository();
    }

    /**
     * Marca todas las notificaciones del usuario actual como leídas
     */
    public function marcarTodasLeidas(): void
    {
        $userId = Session::get('user_id');
        $rol = Session::get('rol');

        if ($userId) {
            $this->repo->markAllAsRead($userId, $rol);
        }

        // Redirigir de vuelta a donde estaba
        $referer = $_SERVER['HTTP_REFERER'] ?? APP_URL . '/dashboard';
        Response::redirect($referer);
    }
}
