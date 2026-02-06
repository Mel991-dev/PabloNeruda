<?php
/**
 * Script para generar hash de contraseña y actualizar en BD
 * 
 * IMPORTANTE: Eliminar este archivo después de usarlo por seguridad
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Cargar configuración
require_once __DIR__ . '/../autoload.php';
require_once __DIR__ . '/../config/config.php';

use App\Core\Database;

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generador de Hash - Sistema Escuela</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 800px;
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
        .alert {
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            color: #d63384;
        }
        .btn {
            padding: 10px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        .btn:hover {
            background: #2980b9;
        }
        pre {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
        }
        form {
            margin: 20px 0;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        label {
            font-weight: bold;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔐 Generador de Hash de Contraseñas</h1>
        
        <div class="alert alert-warning">
            <strong>⚠️ IMPORTANTE:</strong> Elimina este archivo después de usarlo por seguridad.
            <br>Archivo: <code>public/generar_hash.php</code>
        </div>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            if ($username && $password) {
                // Generar hash
                $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                
                echo "<div class='alert alert-success'>";
                echo "<strong>✅ Hash generado exitosamente</strong><br><br>";
                echo "<strong>Usuario:</strong> <code>$username</code><br>";
                echo "<strong>Contraseña:</strong> <code>$password</code><br>";
                echo "<strong>Hash:</strong><br>";
                echo "<pre style='word-break: break-all;'>$hash</pre>";
                echo "</div>";
                
                // Intentar actualizar en la base de datos
                try {
                    $db = Database::getInstance()->getConnection();
                    
                    $stmt = $db->prepare("UPDATE usuarios SET password_hash = :hash WHERE username = :username");
                    $stmt->execute([
                        ':hash' => $hash,
                        ':username' => $username
                    ]);
                    
                    if ($stmt->rowCount() > 0) {
                        echo "<div class='alert alert-success'>";
                        echo "<strong>✅ Contraseña actualizada en la base de datos</strong><br><br>";
                        echo "Ya puedes iniciar sesión con:<br>";
                        echo "Usuario: <code>$username</code><br>";
                        echo "Contraseña: <code>$password</code><br><br>";
                        echo "<a href='/pablo_neruda/login' class='btn'>Ir al Login</a>";
                        echo "</div>";
                    } else {
                        echo "<div class='alert alert-danger'>";
                        echo "<strong>❌ No se encontró el usuario:</strong> <code>$username</code><br>";
                        echo "Verifica que el usuario exista en la base de datos.";
                        echo "</div>";
                    }
                    
                } catch (Exception $e) {
                    echo "<div class='alert alert-danger'>";
                    echo "<strong>❌ Error al actualizar en la base de datos:</strong><br>";
                    echo $e->getMessage();
                    echo "</div>";
                    
                    echo "<div class='alert alert-info'>";
                    echo "<strong>SQL Manual:</strong><br>";
                    echo "Puedes ejecutar esto en phpMyAdmin:<br>";
                    echo "<pre>";
                    echo "UPDATE usuarios \n";
                    echo "SET password_hash = '$hash' \n";
                    echo "WHERE username = '$username';";
                    echo "</pre>";
                    echo "</div>";
                }
                
            } else {
                echo "<div class='alert alert-danger'>";
                echo "<strong>❌ Error:</strong> Debes completar todos los campos.";
                echo "</div>";
            }
        }
        ?>

        <h2>Actualizar Contraseña</h2>
        <form method="POST">
            <label for="username">Usuario:</label>
            <input type="text" id="username" name="username" value="admin" required>
            
            <label for="password">Nueva Contraseña:</label>
            <input type="text" id="password" name="password" value="admin123" required>
            
            <button type="submit" class="btn">Generar Hash y Actualizar</button>
        </form>

        <hr style="margin: 30px 0;">

        <h2>Solo Generar Hash (Sin Actualizar BD)</h2>
        <div class="alert alert-info">
            <strong>Hash para "admin123":</strong><br>
            <pre><?php echo password_hash('admin123', PASSWORD_BCRYPT, ['cost' => 12]); ?></pre>
            
            <strong>Hash para "password123":</strong><br>
            <pre><?php echo password_hash('password123', PASSWORD_BCRYPT, ['cost' => 12]); ?></pre>
        </div>

        <hr style="margin: 30px 0;">

        <h2>Verificar Usuarios Existentes</h2>
        <?php
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->query("SELECT id_usuario, username, rol, estado FROM usuarios ORDER BY id_usuario");
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if ($usuarios) {
                echo "<table style='width: 100%; border-collapse: collapse; margin-top: 15px;'>";
                echo "<tr style='background: #f0f0f0;'>";
                echo "<th style='padding: 10px; border: 1px solid #ddd;'>ID</th>";
                echo "<th style='padding: 10px; border: 1px solid #ddd;'>Usuario</th>";
                echo "<th style='padding: 10px; border: 1px solid #ddd;'>Rol</th>";
                echo "<th style='padding: 10px; border: 1px solid #ddd;'>Estado</th>";
                echo "</tr>";
                
                foreach ($usuarios as $user) {
                    echo "<tr>";
                    echo "<td style='padding: 10px; border: 1px solid #ddd; text-align: center;'>{$user['id_usuario']}</td>";
                    echo "<td style='padding: 10px; border: 1px solid #ddd;'><code>{$user['username']}</code></td>";
                    echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$user['rol']}</td>";
                    echo "<td style='padding: 10px; border: 1px solid #ddd;'>{$user['estado']}</td>";
                    echo "</tr>";
                }
                
                echo "</table>";
            } else {
                echo "<div class='alert alert-warning'>";
                echo "<strong>⚠️ No hay usuarios en la base de datos.</strong><br>";
                echo "Ejecuta el script <code>seed_usuarios.sql</code>";
                echo "</div>";
            }
            
        } catch (Exception $e) {
            echo "<div class='alert alert-danger'>";
            echo "<strong>❌ Error al conectar a la base de datos:</strong><br>";
            echo $e->getMessage();
            echo "</div>";
        }
        ?>

        <div class="alert alert-warning" style="margin-top: 30px;">
            <strong>🗑️ IMPORTANTE:</strong> Una vez que hayas actualizado la contraseña, elimina este archivo:
            <pre>rm public/generar_hash.php</pre>
            O elimínalo manualmente desde el explorador de archivos.
        </div>
    </div>
</body>
</html>
