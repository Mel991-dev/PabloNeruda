<?php
$pageTitle = 'Dashboard Coordinador';
ob_start();
?>
<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Estudiantes</h6>
                        <h2 class="mb-0" id="total-estudiantes"><?php echo $stats['total_estudiantes'] ?? 0; ?></h2>
                    </div>
                    <i class="bi bi-person-badge" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Estudiantes con Alergias</h6>
                        <h2 class="mb-0" id="total-alergias"><?php echo $stats['estudiantes_alergias'] ?? 0; ?></h2>
                    </div>
                    <i class="bi bi-exclamation-triangle" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-title mb-0">Matrículas Activas</h6>
                        <h2 class="mb-0" id="total-matriculas"><?php echo $stats['matriculas_activas'] ?? 0; ?></h2>
                    </div>
                    <i class="bi bi-check-circle" style="font-size: 3rem; opacity: 0.5;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-list-check"></i> Acciones Rápidas
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <a href="<?php echo APP_URL; ?>/estudiantes/crear" class="btn btn-primary w-100 mb-2">
                            <i class="bi bi-plus-circle"></i> Registrar Estudiante
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?php echo APP_URL; ?>/estudiantes" class="btn btn-info text-white w-100 mb-2">
                            <i class="bi bi-list"></i> Ver Estudiantes
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="<?php echo APP_URL; ?>/reportes" class="btn btn-success w-100 mb-2">
                            <i class="bi bi-file-earmark-text"></i> Generar Reportes
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
