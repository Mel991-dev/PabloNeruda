<?php
/**
 * Autoloader PSR-4
 * Sistema de Gestión Académica - Escuela Pablo Neruda
 */

spl_autoload_register(function ($class) {
    // Prefijo del namespace del proyecto
    $prefix = 'App\\';
    
    // Directorio base para el namespace
    $base_dir = __DIR__ . '/src/';
    
    // Verificar si la clase usa el namespace del proyecto
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    // Obtener el nombre relativo de la clase
    $relative_class = substr($class, $len);
    
    // Reemplazar el namespace por la estructura de directorios
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    // Si el archivo existe, cargarlo
    if (file_exists($file)) {
        require $file;
    }
});
