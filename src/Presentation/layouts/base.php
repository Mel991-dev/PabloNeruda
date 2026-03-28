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
                <div class="container-fluid flex-nowrap">
                    <div class="d-flex align-items-center overflow-hidden me-3" style="min-width: 0;">
                        <button class="btn btn-light me-2 border-0 d-lg-none flex-shrink-0" id="sidebarToggle" type="button">
                            <i class="bi bi-list fs-4"></i>
                        </button>
                        <h4 class="mb-0 text-truncate text-break"><?php echo $pageTitle ?? 'Dashboard'; ?></h4>
                    </div>
                    <div class="d-flex align-items-center flex-shrink-0">
                                <?php
                                $userIdSession = \App\Core\Session::get('user_id');
                                $rolSession = \App\Core\Session::get('rol');
                                
                                if ($userIdSession):
                                    $notifRepo = new \App\Infrastructure\Repositories\MySQLNotificacionRepository();
                                    $unreadCount = $notifRepo->countUnread($userIdSession, $rolSession);
                                    $notificaciones = $notifRepo->findUnreadByDestino($userIdSession, $rolSession);
                                ?>
                                <div class="dropdown me-3 dropdown-notifications">
                                    <a class="nav-link dropdown-toggle text-dark position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="padding-top: 5px;">
                                        <i class="bi bi-bell-fill fs-5"></i>
                                        <?php if ($unreadCount > 0): ?>
                                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; transform: translate(-30%, -10%);">
                                                <?php echo $unreadCount; ?>
                                            </span>
                                        <?php endif; ?>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="width: 320px; max-height: 400px; overflow-y: auto;">
                                        <li class="bg-light border-bottom"><h6 class="dropdown-header fw-bold text-dark"><i class="bi bi-app-indicator me-2 text-danger"></i>Centro de Alertas</h6></li>
                                        <?php if (empty($notificaciones)): ?>
                                            <li><span class="dropdown-item text-muted small py-3 text-center">No hay alertas recientes 🎉</span></li>
                                        <?php else: ?>
                                            <?php foreach ($notificaciones as $n): ?>
                                                <li>
                                                    <a class="dropdown-item border-bottom py-2" style="white-space: normal;" href="<?php echo $n->getEnlace() ? APP_URL . $n->getEnlace() : '#'; ?>">
                                                        <div class="d-flex w-100 justify-content-between align-items-center">
                                                            <strong class="mb-1 text-danger" style="font-size: 0.85rem;"><i class="bi bi-exclamation-triangle-fill me-1"></i> <?php echo htmlspecialchars($n->getTitulo()); ?></strong>
                                                            <small class="text-muted" style="font-size: 0.7rem;"><?php echo date('H:i', strtotime($n->getFechaCreacion())); ?></small>
                                                        </div>
                                                        <p class="mb-0 text-wrap text-muted" style="font-size: 0.8rem; line-height: 1.2;"><?php echo htmlspecialchars($n->getMensaje()); ?></p>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                            <li class="bg-light sticky-bottom"><a class="dropdown-item text-center text-primary fw-bold small py-2" href="<?php echo APP_URL; ?>/notificaciones/leidas">Marcar todas como leídas <i class="bi bi-check2-all ms-1"></i></a></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                                <?php endif; ?>
                                
                                <span class="me-3 d-none d-sm-inline">
                                    <i class="bi bi-person-circle text-secondary fs-5 align-middle me-1"></i>
                                    <strong><?php echo htmlspecialchars(\App\Core\Session::get('username', 'Usuario')); ?></strong>
                                    <span class="badge bg-primary ms-2">
                                        <?php echo htmlspecialchars($rolSession ?? ''); ?>
                                    </span>
                                </span>
                                <a href="<?php echo APP_URL; ?>/logout" class="btn btn-outline-danger btn-sm rounded-pill px-3">
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
