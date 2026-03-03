<?php

namespace App\Application\Controllers;

use App\Core\{Request, Response, Session};
use App\Domain\Services\AuthService;
use App\Domain\Services\AuditService;
use App\Infrastructure\Repositories\MySQLUsuarioRepository;

/**
 * Controlador de Autenticación
 */
class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $usuarioRepository = new MySQLUsuarioRepository();
        $this->authService = new AuthService($usuarioRepository);
    }

    /**
     * Mostrar formulario de login
     */
    public function showLogin(): void
    {
        // Si ya está autenticado, redirigir al dashboard
        if ($this->authService->isAuthenticated()) {
            Response::redirect(APP_URL . '/dashboard');
            return;
        }

        Response::view('auth.login');
    }

    /**
     * Procesar login
     */
    public function login(): void
    {
        $request = new Request();

        if (!$request->isPost()) {
            Response::redirect(APP_URL . '/login');
            return;
        }

        $username = $request->input('username');
        $password = $request->input('password');

        $result = $this->authService->login($username, $password);

        if (!$result['success']) {
            Session::flash('error', $result['message']);
            Response::redirect(APP_URL . '/login');
            return;
        }

        // Registrar Login en Auditoría
        $auditService = new AuditService();
        $auditService->registrar(
            $result['usuario']->getIdUsuario(),
            $result['usuario']->getRol(),
            'LOGIN',
            'Autenticación',
            'Inicio de sesión exitoso desde IP ' . ($_SERVER['REMOTE_ADDR'] ?? 'Desconocida')
        );

        Session::flash('success', '¡Bienvenido, ' . $result['usuario']->getUsername() . '!');
        Response::redirect(APP_URL . '/dashboard');
    }

    public function logout(): void
    {
        // Registrar Logout antes de destruir la sesión
        $userId = Session::get('user_id');
        $userRole = Session::get('rol');

        if ($userId && $userRole) {
            $auditService = new AuditService();
            $auditService->registrar(
                $userId,
                $userRole,
                'LOGOUT',
                'Autenticación',
                'Cierre de sesión manual'
            );
        }

        $this->authService->logout();
        Session::flash('success', 'Has cerrado sesión exitosamente');
        Response::redirect(APP_URL . '/login');
    }
}
