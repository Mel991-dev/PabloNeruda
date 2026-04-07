<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Contraseña - <?php echo APP_NAME; ?></title>
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
            font-size: 1.75rem; font-weight: 800;
            color: #111; text-align: center; margin-bottom: 8px;
        }
        .recovery-card p.subtitle {
            color: #777; font-size: 0.9rem; text-align: center;
            margin-bottom: 32px; line-height: 1.6;
        }
        .btn-primary-custom {
            background: #106018; color: #fff;
            border: none; border-radius: 12px;
            padding: 15px; font-weight: 700;
            font-size: 0.95rem; width: 100%;
            transition: all 0.3s ease; margin-top: 8px;
        }
        .btn-primary-custom:hover {
            background: #0d4d13;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16,96,24,0.25);
        }
        .back-link {
            display: block; text-align: center; margin-top: 20px;
            color: #888; font-size: 0.9rem; text-decoration: none; transition: color 0.2s;
        }
        .back-link:hover { color: #106018; }
        /* Password toggle */
        .pw-wrap { position: relative; margin-bottom: 20px; }
        .pw-wrap i.icon-lock {
            position: absolute; left: 20px; top: 50%;
            transform: translateY(-50%); color: #888; font-size: 1.2rem; z-index: 10;
        }
        .pw-wrap .form-control {
            padding: 18px 50px 18px 55px;
            background: #f0f0f0; border: none;
            border-radius: 12px; font-size: 16px;
            transition: all 0.3s ease;
        }
        .pw-wrap .form-control:focus {
            box-shadow: 0 0 0 3px rgba(16,96,24,0.1);
            background: #e8e8e8; outline: none;
        }
        .pw-toggle {
            position: absolute; right: 16px; top: 50%;
            transform: translateY(-50%); cursor: pointer;
            color: #aaa; font-size: 1.1rem; z-index: 10;
            transition: color 0.2s;
        }
        .pw-toggle:hover { color: #106018; }
        /* Strength bar */
        .strength-bar {
            height: 4px; border-radius: 4px;
            background: #e0e0e0; margin-top: -14px;
            margin-bottom: 20px; overflow: hidden;
        }
        .strength-fill {
            height: 100%; width: 0%; border-radius: 4px;
            transition: width 0.4s ease, background 0.4s ease;
        }
        .strength-label {
            font-size: 0.75rem; color: #aaa;
            margin-top: -16px; margin-bottom: 16px;
            text-align: right;
        }
    </style>
</head>
<body>
    <?php use App\Core\Session; ?>
    <div style="background:#f0f4f1;min-height:100vh;display:flex;align-items:center;justify-content:center;">
        <div class="recovery-card">

            <div class="recovery-icon">
                <i class="bi bi-key"></i>
            </div>

            <h1>Nueva Contraseña</h1>
            <p class="subtitle">
                Crea una contraseña segura de al menos 8 caracteres para proteger tu cuenta.
            </p>

            <?php if ($error = Session::getFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-3" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="<?php echo APP_URL; ?>/new-password" method="POST" id="form-new-pw">

                <!-- Nueva contraseña -->
                <div class="pw-wrap">
                    <i class="bi bi-lock icon-lock"></i>
                    <input
                        type="password"
                        name="password"
                        id="pw-new"
                        class="form-control"
                        placeholder="Nueva contraseña"
                        required
                        minlength="8"
                        autocomplete="new-password"
                    >
                    <i class="bi bi-eye pw-toggle" id="toggle-new" title="Mostrar/Ocultar"></i>
                </div>

                <!-- Barra de fortaleza -->
                <div class="strength-bar"><div class="strength-fill" id="strength-fill"></div></div>
                <div class="strength-label" id="strength-label">Ingresa tu contraseña</div>

                <!-- Confirmar contraseña -->
                <div class="pw-wrap">
                    <i class="bi bi-lock-fill icon-lock"></i>
                    <input
                        type="password"
                        name="password_confirm"
                        id="pw-confirm"
                        class="form-control"
                        placeholder="Confirmar contraseña"
                        required
                        minlength="8"
                        autocomplete="new-password"
                    >
                    <i class="bi bi-eye pw-toggle" id="toggle-confirm" title="Mostrar/Ocultar"></i>
                </div>
                <div id="match-msg" style="font-size:0.8rem;margin-top:-14px;margin-bottom:18px;"></div>

                <button type="submit" class="btn-primary-custom" id="btn-save">
                    <i class="bi bi-shield-lock me-2"></i>Guardar Nueva Contraseña
                </button>
            </form>

            <a href="<?php echo APP_URL; ?>/login" class="back-link">
                <i class="bi bi-arrow-left"></i> Volver al inicio de sesión
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ── Toggle visibilidad contraseña ────────────────────────────
        function togglePw(inputId, toggleId) {
            const inp = document.getElementById(inputId);
            const tog = document.getElementById(toggleId);
            tog.addEventListener('click', () => {
                const show = inp.type === 'password';
                inp.type = show ? 'text' : 'password';
                tog.className = show ? 'bi bi-eye-slash pw-toggle' : 'bi bi-eye pw-toggle';
            });
        }
        togglePw('pw-new', 'toggle-new');
        togglePw('pw-confirm', 'toggle-confirm');

        // ── Fortaleza de contraseña ──────────────────────────────────
        const fill  = document.getElementById('strength-fill');
        const label = document.getElementById('strength-label');

        document.getElementById('pw-new').addEventListener('input', function () {
            const pw = this.value;
            let score = 0;
            if (pw.length >= 8)  score++;
            if (/[A-Z]/.test(pw)) score++;
            if (/[0-9]/.test(pw)) score++;
            if (/[^A-Za-z0-9]/.test(pw)) score++;

            const levels = [
                { pct: '0%',   color: '#e0e0e0', text: 'Ingresa tu contraseña' },
                { pct: '25%',  color: '#e74c3c', text: 'Muy débil' },
                { pct: '50%',  color: '#e67e22', text: 'Débil' },
                { pct: '75%',  color: '#f1c40f', text: 'Aceptable' },
                { pct: '100%', color: '#106018', text: 'Fuerte ✓' },
            ];
            const lv = pw.length === 0 ? levels[0] : levels[score];
            fill.style.width  = lv.pct;
            fill.style.background = lv.color;
            label.textContent = lv.text;
            label.style.color = lv.color === '#e0e0e0' ? '#aaa' : lv.color;
        });

        // ── Verificar coincidencia ───────────────────────────────────
        function checkMatch() {
            const pw1 = document.getElementById('pw-new').value;
            const pw2 = document.getElementById('pw-confirm').value;
            const msg = document.getElementById('match-msg');
            if (!pw2) { msg.textContent = ''; return; }
            if (pw1 === pw2) {
                msg.textContent = '✓ Las contraseñas coinciden';
                msg.style.color = '#106018';
            } else {
                msg.textContent = '✗ Las contraseñas no coinciden';
                msg.style.color = '#e74c3c';
            }
        }
        document.getElementById('pw-new').addEventListener('input', checkMatch);
        document.getElementById('pw-confirm').addEventListener('input', checkMatch);

        // ── Prevenir envío si no coinciden ──────────────────────────
        document.getElementById('form-new-pw').addEventListener('submit', (e) => {
            const pw1 = document.getElementById('pw-new').value;
            const pw2 = document.getElementById('pw-confirm').value;
            if (pw1 !== pw2) {
                e.preventDefault();
                document.getElementById('match-msg').textContent = '✗ Las contraseñas no coinciden';
                document.getElementById('match-msg').style.color = '#e74c3c';
            }
        });
    </script>
</body>
</html>
