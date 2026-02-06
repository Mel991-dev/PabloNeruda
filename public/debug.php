<?php
/**
 * DEBUG - Front Controller
 * Muestra información de depuración del sistema de rutas
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🐛 Debug del Sistema de Rutas</h1>";
echo "<hr>";

echo "<h2>1. Variables del Servidor</h2>";
echo "<pre>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "\n";
echo "QUERY_STRING: " . ($_SERVER['QUERY_STRING'] ?? 'N/A') . "\n";
echo "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n";
echo "</pre>";

echo "<h2>2. Carga de Archivos</h2>";

// Intentar cargar autoload
if (file_exists(__DIR__ . '/../autoload.php')) {
    echo "✅ autoload.php encontrado<br>";
    require_once __DIR__ . '/../autoload.php';
} else {
    echo "❌ autoload.php NO encontrado<br>";
}

// Intentar cargar config
if (file_exists(__DIR__ . '/../config/config.php')) {
    echo "✅ config.php encontrado<br>";
    try {
        require_once __DIR__ . '/../config/config.php';
        echo "✅ config.php cargado sin errores<br>";
        echo "APP_URL: " . APP_URL . "<br>";
    } catch (Exception $e) {
        echo "❌ Error al cargar config.php: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ config.php NO encontrado<br>";
}

echo "<h2>3. Clase Request</h2>";
if (class_exists('App\Core\Request')) {
    echo "✅ Clase Request encontrada<br>";
    
    try {
        $request = new App\Core\Request();
        echo "<pre>";
        echo "URI: " . $request->uri() . "\n";
        echo "Method: " . $request->method() . "\n";
        echo "</pre>";
    } catch (Exception $e) {
        echo "❌ Error al instanciar Request: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ Clase Request NO encontrada<br>";
}

echo "<h2>4. Clase Router</h2>";
if (class_exists('App\Core\Router')) {
    echo "✅ Clase Router encontrada<br>";
} else {
    echo "❌ Clase Router NO encontrada<br>";
}

echo "<h2>5. Prueba de Ruta /login</h2>";
if (class_exists('App\Core\Router') && class_exists('App\Core\Request')) {
    try {
        $request = new App\Core\Request();
        $router = new App\Core\Router($request);
        
        // Registrar ruta de prueba
        $router->get('/login', function() {
            echo "<p style='color: green; font-weight: bold;'>✅ RUTA /login FUNCIONA!</p>";
        });
        
        echo "Ruta /login registrada. Intentando ejecutar...<br>";
        
        // Ejecutar router
        $router->run();
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
} else {
    echo "❌ No se pueden cargar las clases necesarias<br>";
}

echo "<h2>6. AuthController</h2>";
if (class_exists('App\Application\Controllers\AuthController')) {
    echo "✅ AuthController encontrado<br>";
} else {
    echo "❌ AuthController NO encontrado<br>";
}
