<?php

namespace App\Domain\Services;

use App\Domain\Entities\Usuario;
use App\Domain\Repositories\UsuarioRepositoryInterface;
use App\Core\Session;

/**
 * Servicio de Autenticación
 */
class AuthService
{
    private UsuarioRepositoryInterface $usuarioRepository;

    public function __construct(UsuarioRepositoryInterface $usuarioRepository)
    {
        $this->usuarioRepository = $usuarioRepository;
    }

    /**
     * Intentar login de usuario
     */
    public function login(string $username, string $password): array
    {
        // Validación básica
        if (empty($username) || empty($password)) {
            return [
                'success' => false,
                'message' => 'Usuario y contraseña son obligatorios'
            ];
        }

        // Buscar usuario
        $usuario = $this->usuarioRepository->findByUsername($username);

        if (!$usuario) {
            return [
                'success' => false,
                'message' => 'Credenciales inválidas'
            ];
        }

        // Verificar si está activo
        if (!$usuario->isActivo()) {
            return [
                'success' => false,
                'message' => 'Usuario inactivo. Contacte al administrador'
            ];
        }

        // Verificar contraseña
        if (!password_verify($password, $usuario->getPasswordHash())) {
            return [
                'success' => false,
                'message' => 'Credenciales inválidas'
            ];
        }

        // Actualizar último acceso
        $this->usuarioRepository->updateLastAccess($usuario->getIdUsuario());

        // Crear sesión
        Session::regenerate();
        Session::set('user_id', $usuario->getIdUsuario());
        Session::set('username', $usuario->getUsername());
        Session::set('rol', $usuario->getRol());
        Session::set('fk_profesor', $usuario->getFkProfesor());
        Session::set('logged_in', true);
        Session::set('last_activity', time());

        return [
            'success' => true,
            'message' => 'Login exitoso',
            'usuario' => $usuario
        ];
    }

    /**
     * Cerrar sesión
     */
    public function logout(): void
    {
        Session::destroy();
    }

    /**
     * Verificar si el usuario está autenticado
     */
    public function isAuthenticated(): bool
    {
        return Session::get('logged_in', false) === true;
    }

    /**
     * Obtener usuario actual de la sesión
     */
    public function getCurrentUser(): ?Usuario
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        $userId = Session::get('user_id');
        return $this->usuarioRepository->findById($userId);
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole(string ...$roles): bool
    {
        if (!$this->isAuthenticated()) {
            return false;
        }

        $userRole = Session::get('rol');
        return in_array($userRole, $roles);
    }

    /**
     * Hash de contraseña
     */
    public function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_ALGO, ['cost' => HASH_COST]);
    }

    /**
     * Crear nuevo usuario
     */
    public function createUser(array $data): array
    {
        // Validar que el username no exista
        $existingUser = $this->usuarioRepository->findByUsername($data['username']);
        if ($existingUser) {
            return [
                'success' => false,
                'message' => 'El nombre de usuario ya está en uso'
            ];
        }

        // Crear usuario
        $usuario = new Usuario();
        $usuario->setUsername($data['username']);
        $usuario->setPasswordHash($this->hashPassword($data['password']));
        $usuario->setRol($data['rol']);
        $usuario->setFkProfesor($data['fk_profesor'] ?? null);
        $usuario->setEstado($data['estado'] ?? 'Activo');

        try {
            $id = $this->usuarioRepository->save($usuario);
            return [
                'success' => true,
                'message' => 'Usuario creado exitosamente',
                'id' => $id
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al crear usuario: ' . $e->getMessage()
            ];
        }
    }
}
