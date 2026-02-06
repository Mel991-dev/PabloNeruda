<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/custom.css">
    <style>
        .container {
            background: linear-gradient(135deg, rgb(255,255, 0) 0%, rgba(236, 236, 66, 1) 100%);
            min-height: 100vh;
            min-width: 100vw;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 450px;
            width: 100%;
            max-height: 610px;
        }
        .login-header {
            background: #106018;
            color: white;
            padding: 30px;
            text-align: center;
        }
        .login-body {
            padding: 40px 30px;
        }
        .btn-login {
            background: #106018;
            border: none;
            padding: 12px;
            font-weight: 600;
        }
        .btn-login:hover {
            background: #0a3d0f;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(16, 96, 24, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-card">
            <div class="login-header text-center">
                <img src="<?php echo APP_URL; ?>/assets/images/escudo.jpg" alt="Logo" class="mb-3" style="width: 80px; border-radius: 50%; border: 3px solid #FFD700;">
                <h2 class="mb-1">Escuela Pablo Neruda</h2>
                <p class="mb-0 text-white-50">Sistema de Gestión Académica</p>
            </div>
            <div class="login-body">
                <?php
                use App\Core\Session;
                
                // Mostrar mensajes flash
                if ($error = Session::getFlash('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($success = Session::getFlash('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill"></i> <?php echo htmlspecialchars($success); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="<?php echo APP_URL; ?>/login" method="POST" class="needs-validation" novalidate>
                    <div class="mb-4">
                        <label for="username" class="form-label">Usuario</label>
                        <input type="text" 
                               class="form-control form-control-lg" 
                               id="username" 
                               name="username" 
                               required 
                               autofocus
                               placeholder="Ingresa tu usuario">
                        <div class="invalid-feedback">
                            Por favor ingresa tu usuario
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" 
                               class="form-control form-control-lg" 
                               id="password" 
                               name="password" 
                               required
                               placeholder="Ingresa tu contraseña">
                        <div class="invalid-feedback">
                            Por favor ingresa tu contraseña
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg btn-login text-white">
                            Iniciar Sesión
                        </button>
                    </div>
                </form>

                <div class="mt-4 text-center text-muted">
                    <small>
                        <strong>Usuarios por defecto:</strong><br>
                        Usuario: <code>admin</code> | Contraseña: <code>admin123</code>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Validación de Bootstrap
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
</body>
</html>
