<?php
$pageTitle = 'Alertas Tempranas - Orientación';
ob_start();
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="fw-bold mb-0"><i class="bi bi-shield-exclamation text-danger"></i> Módulo de Orientación</h2>
        <p class="text-muted">Gestión de alertas tempranas y seguimiento estudiantil.</p>
    </div>
    <div class="col-md-6 text-md-end">
        <a href="<?php echo APP_URL; ?>/orientacion/seguimientos" class="btn btn-outline-primary me-2">
            <i class="bi bi-list-check"></i> Todos los Seguimientos
        </a>
        <a href="<?php echo APP_URL; ?>/estudiantes" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Nuevo Caso
        </a>
    </div>
</div>

<!-- Resumen de Alertas -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card shadow-sm border-0 border-start border-4 border-danger">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-danger"><i class="bi bi-graph-down-arrow"></i> Alertas de Riesgo Académico</h5>
                <span class="badge bg-danger"><?php echo count($alertas); ?> Estudiantes</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Estudiante</th>
                                <th>Curso</th>
                                <th class="text-center">Materias Perdidas</th>
                                <th class="text-center">Promedio General</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($alertas)): ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="bi bi-check-circle text-success fs-1 mb-2 d-block"></i>
                                        <p class="text-muted">No hay alertas académicas activas en este momento.</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($alertas as $alerta): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold text-dark"><?php echo htmlspecialchars($alerta['nombre_completo']); ?></div>
                                            <small class="text-muted">ID: <?php echo $alerta['id_estudiante']; ?></small>
                                        </td>
                                        <td><span class="badge bg-secondary"><?php echo $alerta['nombre_curso']; ?></span></td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill bg-danger fs-6"><?php echo $alerta['materias_perdidas']; ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="fw-bold <?php echo $alerta['promedio_general'] < 3.0 ? 'text-danger' : 'text-warning'; ?>">
                                                <?php echo number_format($alerta['promedio_general'], 1); ?>
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                <a href="<?php echo APP_URL; ?>/orientacion/nuevo?id_estudiante=<?php echo $alerta['id_estudiante']; ?>" class="btn btn-sm btn-primary" title="Iniciar Seguimiento">
                                                    <i class="bi bi-plus-lg"></i> Intervenir
                                                </a>
                                                <a href="<?php echo APP_URL; ?>/orientacion/historial?id=<?php echo $alerta['id_estudiante']; ?>" class="btn btn-sm btn-outline-info" title="Ver Historial">
                                                    <i class="bi bi-clock-history"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alertas de Vulnerabilidad Social -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 border-start border-4 border-warning mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-warning-emphasis"><i class="bi bi-people-fill"></i> Estudiantes en Situación de Vulnerabilidad</h5>
                <span class="badge bg-warning text-dark"><?php echo count($alertas_vulnerabilidad); ?> Casos</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Estudiante</th>
                                <th>Curso</th>
                                <th>Condición de Riesgo</th>
                                <th class="text-end pe-4">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($alertas_vulnerabilidad)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">
                                        No hay alertas de vulnerabilidad social pendientes.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($alertas_vulnerabilidad as $av): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold"><?php echo htmlspecialchars($av['nombre_completo']); ?></div>
                                            <small class="text-muted">ID: <?php echo $av['id_estudiante']; ?></small>
                                        </td>
                                        <td><span class="badge bg-light text-dark border"><?php echo $av['nombre_curso']; ?></span></td>
                                        <td>
                                            <?php if ($av['es_victima']): ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 me-1">Víctima Conflicto</span>
                                            <?php endif; ?>
                                            <?php if ($av['es_desplazado']): ?>
                                                <span class="badge bg-warning bg-opacity-10 text-warning-emphasis border border-warning border-opacity-25 me-1">Pob. Desplazada</span>
                                            <?php endif; ?>
                                            <?php if ($av['falta_servicios']): ?>
                                                <span class="badge bg-info bg-opacity-10 text-info-emphasis border border-info border-opacity-25 me-1">Sin Serv. Básicos</span>
                                            <?php endif; ?>
                                            <?php if ($av['limitaciones_fisicas'] || $av['limitaciones_sensoriales']): ?>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">Disc./Lim. Física</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                <a href="<?php echo APP_URL; ?>/orientacion/nuevo?id_estudiante=<?php echo $av['id_estudiante']; ?>" class="btn btn-sm btn-primary">
                                                    Intervenir
                                                </a>
                                                <a href="<?php echo APP_URL; ?>/orientacion/historial?id=<?php echo $av['id_estudiante']; ?>" class="btn btn-sm btn-outline-info">
                                                    <i class="bi bi-clock-history"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require_once VIEWS_PATH . '/layouts/base.php'; 
?>
