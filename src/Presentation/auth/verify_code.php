<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificar Código - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/login.css">
    <style>
        .recovery-card {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 15px 45px rgba(0,0,0,0.45);
            width: 520px;
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
        /* Cajas de dígitos */
        .digit-boxes { display: flex; gap: 10px; justify-content: center; margin-bottom: 32px; }
        .digit-input {
            width: 56px; height: 68px;
            text-align: center; font-size: 28px; font-weight: 800;
            border: 2px solid #e0e0e0; border-radius: 12px;
            background: #f4f7f6; color: #106018;
            transition: border-color 0.25s, box-shadow 0.25s;
            outline: none;
        }
        .digit-input:focus {
            border-color: #106018;
            box-shadow: 0 0 0 3px rgba(16,96,24,0.12);
            background: #edf5ee;
        }
        .digit-input.filled { border-color: #106018; background: #edf5ee; }
        /* Temporizador */
        .timer-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: #fffbeb; border: 1px solid #FFD700;
            border-radius: 999px; padding: 6px 16px;
            font-size: 0.85rem; color: #7a6400; font-weight: 600;
            margin-bottom: 28px;
        }
        .timer-badge.expired { background: #fff0f0; border-color: #f5a5a5; color: #c0392b; }
        .btn-primary-custom {
            background: #106018; color: #fff;
            border: none; border-radius: 12px;
            padding: 15px; font-weight: 700;
            font-size: 0.95rem; width: 100%;
            transition: all 0.3s ease;
        }
        .btn-primary-custom:hover:not(:disabled) {
            background: #0d4d13;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16,96,24,0.25);
        }
        .btn-primary-custom:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
        .back-link {
            display: block; text-align: center; margin-top: 20px;
            color: #888; font-size: 0.9rem; text-decoration: none; transition: color 0.2s;
        }
        .back-link:hover { color: #106018; }
    </style>
</head>
<body>
    <?php
    use App\Core\Session;
    $emailDisplay = Session::get('reset_email_display', 'tu correo registrado');
    ?>
    <div style="background:#f0f4f1;min-height:100vh;display:flex;align-items:center;justify-content:center;">
        <div class="recovery-card">

            <div class="recovery-icon">
                <i class="bi bi-shield-check"></i>
            </div>

            <h1>Verificar Código</h1>
            <p class="subtitle">
                Ingresa el código de 6 dígitos que enviamos a<br>
                <strong style="color:#106018;"><?php echo htmlspecialchars($emailDisplay); ?></strong>
            </p>

            <?php if ($error = Session::getFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-3" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <?php echo htmlspecialchars($error); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Temporizador 15 minutos -->
            <div class="text-center">
                <div class="timer-badge" id="timer-badge">
                    <i class="bi bi-clock"></i>
                    <span id="timer">15:00</span>
                </div>
            </div>

            <form action="<?php echo APP_URL; ?>/verify-code" method="POST" id="form-verify">
                <!-- Input oculto que contiene el código completo -->
                <input type="hidden" name="token" id="token-hidden">

                <!-- Cajas de un dígito -->
                <div class="digit-boxes" id="digit-boxes">
                    <input type="text" maxlength="1" class="digit-input" data-index="0" id="d0" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                    <input type="text" maxlength="1" class="digit-input" data-index="1" id="d1" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                    <input type="text" maxlength="1" class="digit-input" data-index="2" id="d2" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                    <input type="text" maxlength="1" class="digit-input" data-index="3" id="d3" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                    <input type="text" maxlength="1" class="digit-input" data-index="4" id="d4" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                    <input type="text" maxlength="1" class="digit-input" data-index="5" id="d5" inputmode="numeric" pattern="[0-9]" autocomplete="off">
                </div>

                <button type="submit" class="btn-primary-custom" id="btn-verify" disabled>
                    <i class="bi bi-check-lg me-2"></i>Verificar Código
                </button>
            </form>

            <a href="<?php echo APP_URL; ?>/forgot-password" class="back-link mt-3">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Solicitar nuevo código
            </a>
            <a href="<?php echo APP_URL; ?>/login" class="back-link">
                <i class="bi bi-arrow-left"></i> Volver al inicio de sesión
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // ── Lógica dígitos ────────────────────────────────────────────
        const inputs = document.querySelectorAll('.digit-input');
        const hidden = document.getElementById('token-hidden');
        const btnVerify = document.getElementById('btn-verify');

        inputs.forEach((input, i) => {
            input.addEventListener('input', (e) => {
                const val = e.target.value.replace(/\D/g, '');
                e.target.value = val.slice(-1);
                if (val && i < inputs.length - 1) inputs[i + 1].focus();
                updateToken();
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !e.target.value && i > 0) {
                    inputs[i - 1].focus();
                    inputs[i - 1].value = '';
                    updateToken();
                }
            });
            input.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasted = (e.clipboardData || window.clipboardData)
                    .getData('text').replace(/\D/g, '').slice(0, 6);
                pasted.split('').forEach((ch, j) => {
                    if (inputs[j]) inputs[j].value = ch;
                });
                if (inputs[pasted.length - 1]) inputs[Math.min(pasted.length, inputs.length - 1)].focus();
                updateToken();
            });
        });

        function updateToken() {
            const code = Array.from(inputs).map(i => i.value).join('');
            hidden.value = code;
            btnVerify.disabled = code.length < 6;
            inputs.forEach(i => i.classList.toggle('filled', i.value !== ''));
        }

        // ── Temporizador 15 minutos ───────────────────────────────────
        let seconds = 15 * 60;
        const timerEl = document.getElementById('timer');
        const badgeEl = document.getElementById('timer-badge');

        const countdown = setInterval(() => {
            seconds--;
            const m = Math.floor(seconds / 60).toString().padStart(2, '0');
            const s = (seconds % 60).toString().padStart(2, '0');
            timerEl.textContent = `${m}:${s}`;

            if (seconds <= 0) {
                clearInterval(countdown);
                timerEl.textContent = 'Expirado';
                badgeEl.classList.add('expired');
                btnVerify.disabled = true;
                inputs.forEach(i => { i.disabled = true; i.style.opacity = '0.5'; });
            } else if (seconds <= 60) {
                badgeEl.classList.add('expired');
            }
        }, 1000);

        // Foco en el primer input
        inputs[0].focus();
    </script>
</body>
</html>
