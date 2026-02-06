<?php
/**
 * Configuración General del Sistema
 * Escuela Pablo Neruda
 */

// Cargar variables de entorno
function loadEnv($path) {
    if (!file_exists($path)) {
        return;
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
            putenv("$name=$value");
        }
    }
}

// Cargar .env
loadEnv(__DIR__ . '/../.env');

// Configuración de errores según entorno
if (getenv('APP_DEBUG') === 'true') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Zona horaria
date_default_timezone_set('America/Bogota');

// Constantes de la aplicación
define('APP_NAME', getenv('APP_NAME') ?: 'Sistema Escuela Pablo Neruda');
define('APP_ENV', getenv('APP_ENV') ?: 'production');
define('APP_URL', getenv('APP_URL') ?: 'http://localhost');
define('APP_DEBUG', getenv('APP_DEBUG') === 'true');

// Constantes de base de datos
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_PORT', getenv('DB_PORT') ?: '3306');
define('DB_NAME', getenv('DB_NAME') ?: 'escuela_pablo_neruda');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');

// Constantes de sesión
define('SESSION_LIFETIME', (int)(getenv('SESSION_LIFETIME') ?: 1800)); // 30 minutos
define('SESSION_NAME', getenv('SESSION_NAME') ?: 'escuela_session');

// Constantes de seguridad
define('PASSWORD_ALGO', PASSWORD_BCRYPT);
define('HASH_COST', (int)(getenv('HASH_COST') ?: 12));

// Constantes de uploads
define('UPLOAD_MAX_SIZE', (int)(getenv('UPLOAD_MAX_SIZE') ?: 2097152)); // 2MB
define('UPLOAD_PATH', getenv('UPLOAD_PATH') ?: 'uploads/documentos');
define('UPLOAD_ALLOWED_TYPES', explode(',', getenv('UPLOAD_ALLOWED_TYPES') ?: 'pdf'));

// Paths del sistema
define('ROOT_PATH', dirname(__DIR__));
define('SRC_PATH', ROOT_PATH . '/src');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('VIEWS_PATH', SRC_PATH . '/Presentation');
define('LOGS_PATH', ROOT_PATH . '/logs');
define('BACKUP_PATH', ROOT_PATH . '/backups');

// Configuración de sesión
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Cambiar a 1 si usas HTTPS
ini_set('session.gc_maxlifetime', SESSION_LIFETIME);

return [
    'app' => [
        'name' => APP_NAME,
        'env' => APP_ENV,
        'debug' => APP_DEBUG,
        'url' => APP_URL
    ],
    'db' => [
        'host' => DB_HOST,
        'port' => DB_PORT,
        'name' => DB_NAME,
        'user' => DB_USER,
        'pass' => DB_PASS,
        'charset' => DB_CHARSET
    ],
    'session' => [
        'lifetime' => SESSION_LIFETIME,
        'name' => SESSION_NAME
    ]
];
