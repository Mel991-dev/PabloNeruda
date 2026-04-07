<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/login.css">
    <style>
        .recovery-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 15px 45px rgba(0,0,0,0.45);
            width: 480px;
            max-width: 95vw;
            padding: 52px 48px 44px;
        }
        .recovery-icon {
            width: 72px; height: 72px;
            background: linear-gradient(135deg, #106018, #1a8a24);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 28px;
            box-shadow: 0 8px 24px rgba(16,96,24,0.25);
        }
        .recovery-icon i { font-size: 2rem; color: #FFD700; }
        .recovery-card h1 {
            font-size: 1.8rem; font-weight: 800;
            color: #111; text-align: center; margin-bottom: 8px;
        }
        .recovery-card p.subtitle {
            color: #777; font-size: 0.95rem; text-align: center;
            margin-bottom: 32px; line-height: 1.6;
        }
        .btn-primary-custom {
            background: #106018; color: #fff;
            border: none; border-radius: 12px;
            padding: 15px; font-weight: 700;
            font-size: 0.95rem; letter-spacing: 0.5px;
            width: 100%; transition: all 0.3s ease;
        }
        .btn-primary-custom:hover {
            background: #0d4d13;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16,96,24,0.25);
        }
        .back-link {
            display: block; text-align: center;
            margin-top: 24px; color: #888;
            font-size: 0.9rem; text-decoration: none;
            transition: color 0.2s;
        }
        .back-link:hover { color: #106018; }
        .back-link i { margin-right: 4px; }
    </style>
</head>
<body>
    <div style="background:#f0f4f1;min-height:100vh;display:flex;align-items:center;justify-content:center;">
        <div class="recovery-card">

            <div class="recovery-icon">
                <i class="bi bi-envelope-paper"></i>
            </div>

            <h1>¿Olvidaste tu contraseña?</h1>
            <p class="subtitle">
                Ingresa el correo electrónico asociado a tu cuenta y te enviaremos un código de verificación de 6 dígitos.
            </p>

            <?php use App\Core\Session; ?>

            <?php if ($error = Session::getFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($success = Session::getFlash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    <?php echo htmlspecialchars($success); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="<?php echo APP_URL; ?>/forgot-password" method="POST" id="form-forgot">
                <div class="input-group-custom mb-4">
                    <i class="bi bi-envelope"></i>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control"
                        placeholder="correo@ejemplo.com"
                        required
                        autofocus
                        autocomplete="email"
                    >
                </div>

                <button type="submit" class="btn-primary-custom" id="btn-submit">
                    <span id="btn-text"><i class="bi bi-send me-2"></i>Enviar Código</span>
                    <span id="btn-loading" style="display:none;">
                        <span class="spinner-border spinner-border-sm me-2" role="status"></span>Enviando...
                    </span>
                </button>
            </form>

            <a href="<?php echo APP_URL; ?>/login" class="back-link">
                <i class="bi bi-arrow-left"></i> Volver al inicio de sesión
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('form-forgot').addEventListener('submit', function () {
            document.getElementById('btn-text').style.display = 'none';
            document.getElementById('btn-loading').style.display = 'inline-flex';
            document.getElementById('btn-submit').disabled = true;
        });
    </script>
</body>
</html>
