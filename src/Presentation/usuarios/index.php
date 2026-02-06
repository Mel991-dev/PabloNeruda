<?php
$pageTitle = 'Gestión de Usuarios';
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-people-fill text-primary"></i> Gestión de Usuarios</h2>
    <a href="<?php echo APP_URL; ?>/usuarios/crear" class="btn btn-primary">
        <i class="bi bi-person-plus-fill"></i> Nuevo Usuario
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3">ID</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th>Último Acceso</th>
                        <th class="text-end pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usr): ?>
                        <tr>
                            <td class="ps-3"><?php echo $usr->getIdUsuario(); ?></td>
                            <td class="fw-bold">
                                <?php echo $usr->getUsername(); ?>
                            </td>
                            <td>
                                <span class="badge bg-secondary"><?php echo $usr->getRol(); ?></span>
                            </td>
                            <td>
                                <?php if ($usr->isActivo()): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success">Activo</span>
                                <?php else: ?>
                                    <span class="badge bg-danger bg-opacity-10 text-danger">Inactivo</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-muted small">
                                <?php echo $usr->getUltimoAcceso() ?? 'Nunca'; ?>
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group">
                                    <a href="<?php echo APP_URL; ?>/usuarios/editar?id=<?php echo $usr->getIdUsuario(); ?>" 
                                       class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    
                                    <?php if ($usr->getIdUsuario() !== \App\Core\Session::get('user_id')): ?>
                                        <?php if ($usr->isActivo()): ?>
                                            <a href="<?php echo APP_URL; ?>/usuarios/estado?id=<?php echo $usr->getIdUsuario(); ?>" 
                                               class="btn btn-sm btn-outline-warning" title="Suspender"
                                               onclick="return confirm('¿Seguro deseas suspender este usuario? No podrá iniciar sesión.');">
                                                <i class="bi bi-pause-circle"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo APP_URL; ?>/usuarios/estado?id=<?php echo $usr->getIdUsuario(); ?>" 
                                               class="btn btn-sm btn-outline-success" title="Reactivar">
                                                <i class="bi bi-play-circle"></i>
                                            </a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require_once VIEWS_PATH . '/layouts/base.php'; 
?>
