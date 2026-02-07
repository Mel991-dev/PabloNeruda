<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Dashboard'; ?> - <?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/assets/css/custom.css">
</head>
<body>
    <div class="app-layout">
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                <img src="<?php echo APP_URL; ?>/assets/images/escudo.jpg" alt="Logo">
                <div class="brand-text">
                    <h4>Escuela P. Neruda</h4>
                    <p>Sistema de Gestión</p>
                </div>
            </div>
            <hr class="bg-white">
            <div class="sidebar-menu">
                <?php include VIEWS_PATH . '/layouts/sidebar.php'; ?>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content" id="mainContent">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light sticky-top mb-4 shadow-sm">
                <div class="container-fluid">
                    <h4 class="mb-0"><?php echo $pageTitle ?? 'Dashboard'; ?></h4>
                    <div class="d-flex align-items-center">
                        <span class="me-3 d-none d-sm-inline">
                            <i class="bi bi-person-circle"></i>
                            <strong><?php echo htmlspecialchars(\App\Core\Session::get('username', 'Usuario')); ?></strong>
                            <span class="badge bg-primary ms-2">
                                <?php echo htmlspecialchars(\App\Core\Session::get('rol', '')); ?>
                            </span>
                        </span>
                        <a href="<?php echo APP_URL; ?>/logout" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-box-arrow-right"></i> Salir
                        </a>
                    </div>
                </div>
            </nav>

            <div class="px-md-4 py-2">
                <!-- Flash Messages -->
                <?php
                use App\Core\Session;
                
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

                <!-- Page Content -->
                <?php echo $content ?? ''; ?>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo APP_URL; ?>/assets/js/app.js"></script>
    <?php if (isset($extraScripts)) echo $extraScripts; ?>
</body>
</html>
