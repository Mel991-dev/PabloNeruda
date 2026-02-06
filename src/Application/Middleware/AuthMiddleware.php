<?php

namespace App\Application\Middleware;

use App\Core\{Session, Response};

/**
 * Middleware de Autenticación
 */
class AuthMiddleware
{
    /**
     * Manejar la petición
     */
    public function handle($request, array $params = []): void
    {
        // Verificar si está logueado
        if (!Session::get('logged_in', false)) {
            Session::flash('error', 'Debes iniciar sesión para acceder');
            Response::redirect(APP_URL . '/login');
        }

        // Verificar si la sesión ha expirado
        if (Session::isExpired()) {
            Session::destroy();
            Session::flash('error', 'Tu sesión ha expirado. Por favor inicia sesión nuevamente');
            Response::redirect(APP_URL . '/login');
        }
    }
}
