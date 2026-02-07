<?php
$pageTitle = 'Dashboard Administrador';
ob_start();
?>

<div class="row">
    <!-- Estadísticas Rápidas -->
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Usuarios</h6>
                        <h2 class="mb-0"><?php echo $stats['usuarios_activos']; ?></h2>
                    </div>
                    <i class="bi bi-people" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Estudiantes</h6>
                        <h2 class="mb-0"><?php echo $stats['total_estudiantes']; ?></h2>
                    </div>
                    <i class="bi bi-person-badge" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Cursos</h6>
                        <h2 class="mb-0"><?php echo $stats['total_cursos']; ?></h2>
                    </div>
                    <i class="bi bi-book" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Profesores</h6>
                        <h2 class="mb-0"><?php echo $stats['total_profesores']; ?></h2>
                    </div>
                    <i class="bi bi-person-workspace" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-activity"></i> Actividad Reciente
            </div>
            <div class="card-body">
                <p class="text-muted">No hay actividad reciente</p>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-list-check"></i> Acciones Rápidas
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo APP_URL; ?>/usuarios/crear" class="btn btn-warning">
                        <i class="bi bi-plus-circle"></i> Crear Usuario
                    </a>
                    <a href="<?php echo APP_URL; ?>/cursos/crear" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Crear Curso
                    </a>
                    <a href="<?php echo APP_URL; ?>/estudiantes" class="btn btn-info text-white">
                        <i class="bi bi-list"></i> Ver Estudiantes
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/base.php';
?>
