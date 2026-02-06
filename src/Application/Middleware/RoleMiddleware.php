<?php

namespace App\Application\Middleware;

use App\Core\{Session, Response};

/**
 * Middleware de Roles
 */
class RoleMiddleware
{
    /**
     * Manejar la petición
     */
    public function handle($request, array $allowedRoles = []): void
    {
        if (empty($allowedRoles)) {
            return; // Sin restricción de roles
        }

        $userRole = Session::get('rol');

        if (!$userRole || !in_array($userRole, $allowedRoles)) {
            Response::error(403, 'No tienes permiso para acceder a esta sección');
        }
    }
}
