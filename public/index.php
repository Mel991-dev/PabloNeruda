<?php
/**
 * Front Controller - Punto de entrada de la aplicación
 * Sistema de Gestión Académica - Escuela Pablo Neruda
 */

// Habilitar reporte de errores para diagnóstico
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Cargar autoloader
require_once __DIR__ . '/../autoload.php';

// Cargar ayudantes de vista y seguridad
require_once __DIR__ . '/../src/Core/ViewHelper.php';

// Cargar configuración
try {
    $config = require_once __DIR__ . '/../config/config.php';
} catch (\Exception $e) {
    die("Error al cargar configuración: " . $e->getMessage());
}

// Usar clases del Core
use App\Core\{Session, Request, Response, Router};

// Iniciar sesión
Session::start();

// Crear instancia de Request
$request = new Request();

// Crear instancia de Router
$router = new Router($request);

// ========== DEFINICIÓN DE RUTAS ==========

// Ruta de inicio
$router->get('/', function() {
    Response::redirect(APP_URL . '/login');
});

// Rutas de autenticación
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// Dashboard
$router->get('/dashboard', 'DashboardController@index', ['auth']);

// Rutas de estudiantes (Coordinador)
$router->get('/estudiantes', 'EstudianteController@index', ['auth', 'role:Coordinador,Rector,Administrador,Orientador']);
$router->get('/estudiantes/crear', 'EstudianteController@create', ['auth', 'role:Coordinador,Administrador']);
$router->post('/estudiantes/guardar', 'EstudianteController@store', ['auth', 'role:Coordinador,Administrador']);
$router->get('/estudiantes/editar', 'EstudianteController@edit', ['auth', 'role:Coordinador,Administrador']);
$router->post('/estudiantes/actualizar', 'EstudianteController@update', ['auth', 'role:Coordinador,Administrador']);
$router->get('/estudiantes/ver', 'EstudianteController@show', ['auth']);

// Rutas de notas (Profesor)
$router->get('/notas', 'NotaController@index', ['auth', 'role:Profesor,Coordinador,Rector,Administrador']);
$router->get('/notas/registrar', 'NotaController@create', ['auth', 'role:Profesor,Administrador']);
$router->post('/notas/guardar', 'NotaController@store', ['auth', 'role:Profesor,Administrador']);

// Rutas de reportes
$router->get('/reportes', 'ReporteController@index', ['auth']);
$router->get('/reportes/boletin', 'ReporteController@boletin', ['auth']);
$router->get('/api/estudiantes', 'ReporteController@apiEstudiantes', ['auth']); // API AJAX

// Rutas de usuarios (Administrador)
$router->get('/usuarios', 'UsuarioController@index', ['auth', 'role:Administrador']);
$router->get('/usuarios/crear', 'UsuarioController@create', ['auth', 'role:Administrador']);
$router->post('/usuarios/guardar', 'UsuarioController@store', ['auth', 'role:Administrador']);
$router->get('/usuarios/editar', 'UsuarioController@edit', ['auth', 'role:Administrador']);
$router->post('/usuarios/actualizar', 'UsuarioController@update', ['auth', 'role:Administrador']);
$router->get('/usuarios/estado', 'UsuarioController@toggleEstado', ['auth', 'role:Administrador']);

// Rutas de cursos (Administrador)
$router->get('/cursos', 'CursoController@index', ['auth', 'role:Administrador,Coordinador,Rector,Profesor']);
$router->get('/cursos/crear', 'CursoController@create', ['auth', 'role:Administrador']);
$router->post('/cursos/guardar', 'CursoController@store', ['auth', 'role:Administrador']);
$router->get('/cursos/editar', 'CursoController@edit', ['auth', 'role:Administrador']);
$router->post('/cursos/actualizar', 'CursoController@update', ['auth', 'role:Administrador']);
$router->get('/cursos/ver', 'CursoController@verEstudiantes', ['auth', 'role:Administrador,Coordinador,Rector,Profesor']);

// Rutas de orientación
$router->get('/orientacion', 'OrientacionController@index', ['auth', 'role:Orientador,Coordinador,Rector,Administrador']);
$router->get('/orientacion/seguimientos', 'OrientacionController@listado', ['auth', 'role:Orientador,Coordinador,Administrador']);
$router->get('/orientacion/nuevo', 'OrientacionController@crear', ['auth', 'role:Orientador,Coordinador,Administrador']);
$router->post('/orientacion/guardar', 'OrientacionController@guardar', ['auth', 'role:Orientador,Coordinador,Administrador']);
$router->get('/orientacion/historial', 'OrientacionController@historial', ['auth', 'role:Orientador,Coordinador,Rector,Administrador']);

// ========== RESOLVER RUTA ==========

try {
    $router->resolve();
} catch (\Exception $e) {
    if (APP_DEBUG) {
        echo "<pre>";
        echo "Error: " . $e->getMessage() . "\n";
        echo "Archivo: " . $e->getFile() . "\n";
        echo "Línea: " . $e->getLine() . "\n";
        echo "Traza:\n" . $e->getTraceAsString();
        echo "</pre>";
    } else {
        Response::error(500, "Ha ocurrido un error. Por favor contacte al administrador.");
    }
}
