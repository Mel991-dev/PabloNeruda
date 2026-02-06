<?php

namespace App\Core;

/**
 * ViewHelper
 * Utilidades para las vistas para mejorar seguridad y legibilidad.
 */
class ViewHelper
{
    /**
     * Escapar HTML para prevenir XSS
     */
    public static function e($value): string
    {
        if ($value === null) return '';
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Formatear fecha a formato humano (d/m/Y)
     */
    public static function date($date): string
    {
        if (!$date) return 'N/R';
        return date('d/m/Y', strtotime($date));
    }

    /**
     * Badge de estado con color
     */
    public static function statusBadge(string $status): string
    {
        $class = match($status) {
            'Activo', 'Aprobado', 'Cerrado' => 'bg-success',
            'Inactivo', 'Reprobado', 'Retirado' => 'bg-danger',
            'En Proceso', 'Pendiente' => 'bg-warning text-dark',
            default => 'bg-secondary'
        };
        
        return "<span class=\"badge $class\">" . self::e($status) . "</span>";
    }
}

/**
 * Función global 'e' para facilitar su uso en vistas
 */
if (!function_exists('e')) {
    function e($value): string {
        return ViewHelper::e($value);
    }
}
