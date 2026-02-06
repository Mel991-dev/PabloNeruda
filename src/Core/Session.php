<?php

namespace App\Core;

/**
 * Clase Session - Gestión de sesiones
 */
class Session
{
    /**
     * Iniciar sesión
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_name(SESSION_NAME);
            session_start();
            
            // Regenerar ID de sesión periódicamente para seguridad
            if (!self::has('last_regeneration')) {
                self::regenerate();
            } elseif (time() - self::get('last_regeneration') > 300) { // Cada 5 minutos
                self::regenerate();
            }
        }
    }

    /**
     * Regenerar ID de sesión
     */
    public static function regenerate(): void
    {
        session_regenerate_id(true);
        self::set('last_regeneration', time());
    }

    /**
     * Establecer un valor en la sesión
     */
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Obtener un valor de la sesión
     */
    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Verificar si existe una clave en la sesión
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Eliminar un valor de la sesión
     */
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destruir la sesión
     */
    public static function destroy(): void
    {
        session_destroy();
        $_SESSION = [];
    }

    /**
     * Obtener todas las variables de sesión
     */
    public static function all(): array
    {
        return $_SESSION ?? [];
    }

    /**
     * Establecer un mensaje flash
     */
    public static function flash(string $key, $value): void
    {
        self::set('flash_' . $key, $value);
    }

    /**
     * Obtener y eliminar un mensaje flash
     */
    public static function getFlash(string $key, $default = null)
    {
        $value = self::get('flash_' . $key, $default);
        self::remove('flash_' . $key);
        return $value;
    }

    /**
     * Verificar si la sesión ha expirado
     */
    public static function isExpired(): bool
    {
        if (!self::has('last_activity')) {
            self::set('last_activity', time());
            return false;
        }

        $elapsed = time() - self::get('last_activity');
        
        if ($elapsed > SESSION_LIFETIME) {
            return true;
        }

        self::set('last_activity', time());
        return false;
    }
}
