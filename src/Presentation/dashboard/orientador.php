<?php
$pageTitle = 'Panel de Orientación';
ob_start();
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="fw-bold"><i class="bi bi-person-heart text-primary"></i> Bienvenido, <?php echo htmlspecialchars($usuario->getUsername()); ?></h2>
        <p class="text-muted">Panel de Bienestar Estudiantil y Alertas Tempranas.</p>
    </div>
</div>

<!-- Estadísticas Rápidas -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 bg-white bg-opacity-25 rounded p-3">
                    <i class="bi bi-journal-text fs-1"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="card-subtitle mb-1">Total Seguimientos</h6>
                    <h2 class="card-title mb-0 fw-bold"><?php echo $stats['total_seguimientos']; ?></h2>
                </div>
            </div>
            <div class="card-footer bg-white bg-opacity-10 border-0">
                <a href="<?php echo APP_URL; ?>/orientacion/seguimientos" class="text-white text-decoration-none small">Ver todos <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-warning text-dark h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 bg-dark bg-opacity-10 rounded p-3">
                    <i class="bi bi-clock-history fs-1"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="card-subtitle mb-1">Casos Abiertos</h6>
                    <h2 class="card-title mb-0 fw-bold"><?php echo $stats['casos_abiertos']; ?></h2>
                </div>
            </div>
            <div class="card-footer bg-dark bg-opacity-10 border-0">
                <span class="small fw-bold text-uppercase">Intervenciones en curso</span>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-danger text-white h-100">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 bg-white bg-opacity-25 rounded p-3">
                    <i class="bi bi-exclamation-triangle fs-1"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h6 class="card-subtitle mb-1">Alertas Académicas</h6>
                    <h2 class="card-title mb-0 fw-bold"><?php echo $stats['alertas_academicas']; ?></h2>
                </div>
            </div>
            <div class="card-footer bg-white bg-opacity-10 border-0">
                <a href="#alertasTable" class="text-white text-decoration-none small">Ver detalles <i class="bi bi-arrow-down"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Tabla de Alertas Académicas -->
    <div class="col-lg-8">
        <div class="card shadow-sm border-0 mb-4" id="alertasTable">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold text-danger"><i class="bi bi-graph-down-arrow"></i> Estudiantes en Riesgo Académico</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Estudiante</th>
                                <th>Curso</th>
                                <th class="text-center">Materias Perdidas</th>
                                <th class="text-center">Promedio</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // Nota: En un entorno real, esto se cargaría vía AJAX o se pasaría desde el controlador
                            // El DashboardController de momento solo pasa stats. 
                            // Sin embargo, para que sea funcional, asumiremos que OrientacionController@index es el dashboard real.
                            // Si stats tuviera la lista de alertas (lo agregaremos), se renderiza aquí.
                            ?>
                            <tr class="table-warning">
                                <td colspan="5" class="text-center py-4">
                                    <i class="bi bi-info-circle"></i> Use el módulo de <strong>Orientación</strong> para ver la lista detallada de alertas.
                                    <br>
                                    <a href="<?php echo APP_URL; ?>/orientacion" class="btn btn-sm btn-outline-primary mt-2">Ir a Orientación</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Accesos Rápidos -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">Acciones Rápidas</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo APP_URL; ?>/orientacion/nuevo" class="btn btn-outline-primary text-start p-3">
                        <i class="bi bi-plus-circle-fill me-2"></i> Iniciar Nuevo Seguimiento
                    </a>
                    <a href="<?php echo APP_URL; ?>/estudiantes" class="btn btn-outline-secondary text-start p-3">
                        <i class="bi bi-people-fill me-2"></i> Consultar Fichas Integrales
                    </a>
                    <a href="<?php echo APP_URL; ?>/orientacion/seguimientos" class="btn btn-outline-info text-start p-3">
                        <i class="bi bi-clipboard-pulse me-2"></i> Ver Historial de Casos
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require_once VIEWS_PATH . '/layouts/base.php'; 
?>
