<?php

namespace App\Application\Controllers;

use App\Core\{Request, Response, Session};
use App\Domain\Services\AuthService;
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

        Session::flash('success', '¡Bienvenido, ' . $result['usuario']->getUsername() . '!');
        Response::redirect(APP_URL . '/dashboard');
    }

    /**
     * Cerrar sesión
     */
    public function logout(): void
    {
        $this->authService->logout();
        Session::flash('success', 'Has cerrado sesión exitosamente');
        Response::redirect(APP_URL . '/login');
    }
}
