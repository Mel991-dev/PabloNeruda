<?php
$pageTitle = 'Dashboard Profesor';
ob_start();
?>
<div class="row">
    <div class="col-md-6">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Mis Cursos</h6>
                        <h2 class="mb-0"><?php echo $stats['total_cursos'] ?? 0; ?></h2>
                    </div>
                    <i class="bi bi-book" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Estudiantes Totales</h6>
                        <h2 class="mb-0"><?php echo $stats['total_estudiantes'] ?? 0; ?></h2>
                    </div>
                    <i class="bi bi-people" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clipboard-check"></i> Gestión de Notas
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <a href="<?php echo APP_URL; ?>/notas/registrar" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-pencil-square"></i> Registrar Notas
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="<?php echo APP_URL; ?>/notas" class="btn btn-info text-white w-100 mb-2">
                            <i class="bi bi-list"></i> Ver Todas las Notas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/base.php';
?>
