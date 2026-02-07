<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/login.css">
</head>
<body>

    <div class="login-container">
        <!-- Form Section -->
        <div class="login-form-section">
            <h1>Iniciar Sesión</h1>
            
            <?php
            use App\Core\Session;
            if ($error = Session::getFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i> <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="<?php echo APP_URL; ?>/login" method="POST">
                <div class="input-group-custom">
                    <i class="bi bi-person"></i>
                    <input type="text" name="username" class="form-control" placeholder="Email o Usuario" required autofocus>
                </div>
                
                <div class="input-group-custom">
                    <i class="bi bi-shield-lock"></i>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>

                <div class="forgot-password">
                    <a href="#">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="btn-submit">INICIAR SESIÓN</button>
            </form>
        </div>

        <!-- Welcome Section -->
        <div class="welcome-section">
            <h2>¡Bienvenido!</h2>
            <p>
                Nos alegra tenerte de vuelta. Nuestro sistema está diseñado para que gestiones la información de tus estudiantes de una manera mucho más ágil, sencilla y eficiente. Juntos seguimos fortaleciendo nuestra comunidad educativa.
            </p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
