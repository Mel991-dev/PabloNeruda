<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico Apache - Sistema Escuela Pablo Neruda</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 10px;
        }
        h2 {
            color: #34495e;
            margin-top: 30px;
        }
        .check-item {
            padding: 15px;
            margin: 10px 0;
            border-radius: 5px;
            border-left: 4px solid #ccc;
        }
        .success {
            background-color: #d4edda;
            border-left-color: #28a745;
        }
        .error {
            background-color: #f8d7da;
            border-left-color: #dc3545;
        }
        .warning {
            background-color: #fff3cd;
            border-left-color: #ffc107;
        }
        .info {
            background-color: #d1ecf1;
            border-left-color: #17a2b8;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
            margin-left: 10px;
        }
        .badge-success { background: #28a745; color: white; }
        .badge-error { background: #dc3545; color: white; }
        .badge-warning { background: #ffc107; color: #000; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Diagnóstico del Sistema Apache</h1>
        
        <?php
        // 1. Verificar PHP
        echo "<h2>1. Información PHP</h2>";
        echo "<div class='check-item success'>";
        echo "✅ <strong>PHP Versión:</strong> " . phpversion();
        echo "<span class='badge badge-success'>FUNCIONANDO</span>";
        echo "</div>";
        
        // 2. Verificar mod_rewrite
        echo "<h2>2. Módulo mod_rewrite</h2>";
        if (function_exists('apache_get_modules')) {
            $modules = apache_get_modules();
            if (in_array('mod_rewrite', $modules)) {
                echo "<div class='check-item success'>";
                echo "✅ <strong>mod_rewrite:</strong> HABILITADO";
                echo "<span class='badge badge-success'>OK</span>";
                echo "</div>";
            } else {
                echo "<div class='check-item error'>";
                echo "❌ <strong>mod_rewrite:</strong> NO HABILITADO";
                echo "<span class='badge badge-error'>ERROR</span>";
                echo "<p style='margin-top:10px;'><strong>SOLUCIÓN:</strong></p>";
                echo "<ol>";
                echo "<li>Click en el ícono de WAMP → Apache → Apache Modules</li>";
                echo "<li>Activar <code>rewrite_module</code></li>";
                echo "<li>Reiniciar Apache</li>";
                echo "</ol>";
                echo "</div>";
            }
        } else {
            echo "<div class='check-item warning'>";
            echo "⚠️ <strong>No se puede verificar:</strong> apache_get_modules() no disponible";
            echo "<p>Verifica manualmente en phpinfo() buscando 'mod_rewrite'</p>";
            echo "</div>";
        }
        
        // 3. Verificar .htaccess
        echo "<h2>3. Archivos .htaccess</h2>";
        
        $htaccess_root = dirname(__DIR__) . '/.htaccess';
        $htaccess_public = __DIR__ . '/.htaccess';
        
        if (file_exists($htaccess_root)) {
            echo "<div class='check-item success'>";
            echo "✅ <strong>.htaccess raíz:</strong> Existe";
            echo "<br><code>" . htmlspecialchars($htaccess_root) . "</code>";
            echo "</div>";
        } else {
            echo "<div class='check-item error'>";
            echo "❌ <strong>.htaccess raíz:</strong> NO existe";
            echo "</div>";
        }
        
        if (file_exists($htaccess_public)) {
            echo "<div class='check-item success'>";
            echo "✅ <strong>.htaccess public:</strong> Existe";
            echo "<br><code>" . htmlspecialchars($htaccess_public) . "</code>";
            echo "</div>";
        } else {
            echo "<div class='check-item error'>";
            echo "❌ <strong>.htaccess public:</strong> NO existe";
            echo "</div>";
        }
        
        // 4. Verificar archivo index.php
        echo "<h2>4. Front Controller</h2>";
        if (file_exists(__DIR__ . '/index.php')) {
            echo "<div class='check-item success'>";
            echo "✅ <strong>index.php:</strong> Existe";
            echo "</div>";
        } else {
            echo "<div class='check-item error'>";
            echo "❌ <strong>index.php:</strong> NO existe";
            echo "</div>";
        }
        
        // 5. Verificar conexión a BD
        echo "<h2>5. Configuración de Base de Datos</h2>";
        $env_file = dirname(__DIR__) . '/.env';
        if (file_exists($env_file)) {
            echo "<div class='check-item success'>";
            echo "✅ <strong>Archivo .env:</strong> Existe";
            echo "</div>";
            
            // Leer .env y verificar BD
            $env_content = file_get_contents($env_file);
            preg_match('/DB_HOST=(.+)/', $env_content, $host);
            preg_match('/DB_NAME=(.+)/', $env_content, $dbname);
            preg_match('/DB_USER=(.+)/', $env_content, $user);
            
            if ($host && $dbname && $user) {
                $db_host = trim($host[1]);
                $db_name = trim($dbname[1]);
                $db_user = trim($user[1]);
                
                echo "<div class='check-item info'>";
                echo "<strong>Configuración:</strong><br>";
                echo "Host: <code>" . htmlspecialchars($db_host) . "</code><br>";
                echo "Base de datos: <code>" . htmlspecialchars($db_name) . "</code><br>";
                echo "Usuario: <code>" . htmlspecialchars($db_user) . "</code>";
                echo "</div>";
            }
        } else {
            echo "<div class='check-item error'>";
            echo "❌ <strong>Archivo .env:</strong> NO existe";
            echo "<p>Copia <code>.env.example</code> a <code>.env</code></p>";
            echo "</div>";
        }
        
        // 6. Test de rutas
        echo "<h2>6. Test de Rutas</h2>";
        echo "<div class='check-item info'>";
        echo "<strong>Prueba estas URLs:</strong><br><br>";
        $base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/pablo_neruda';
        echo "🔗 <a href='$base_url/login' target='_blank'>$base_url/login</a><br>";
        echo "🔗 <a href='$base_url/test.php' target='_blank'>$base_url/test.php</a><br>";
        echo "🔗 <a href='$base_url/dashboard' target='_blank'>$base_url/dashboard</a>";
        echo "</div>";
        
        // 7. Resumen
        echo "<h2>7. Resumen Final</h2>";
        if (function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules())) {
            echo "<div class='check-item success'>";
            echo "✅ <strong>Sistema configurado correctamente</strong>";
            echo "<p>El enrutamiento debería funcionar. Prueba acceder a <code>/login</code></p>";
            echo "</div>";
        } else {
            echo "<div class='check-item error'>";
            echo "❌ <strong>Acción requerida:</strong> Habilitar mod_rewrite";
            echo "<p>Lee el archivo <code>COMO_FUNCIONA_ENRUTAMIENTO.md</code> para instrucciones detalladas.</p>";
            echo "</div>";
        }
        ?>
        
        <div style="margin-top: 30px; padding: 15px; background: #e9ecef; border-radius: 5px;">
            <strong>📚 Documentación:</strong><br>
            📄 <code>COMO_FUNCIONA_ENRUTAMIENTO.md</code> - Explicación completa del sistema<br>
            📄 <code>SOLUCION_DEFINITIVA.md</code> - Guía de configuración
        </div>
    </div>
</body>
</html>
