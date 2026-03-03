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
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold text-danger"><i class="bi bi-graph-up-arrow me-2"></i>Materias con Mayor Reprobación</h6>
            </div>
            <div class="card-body">
                <canvas id="chartReprobacion"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold text-success"><i class="bi bi-pie-chart me-2"></i>Distribución de Rendimiento</h6>
            </div>
            <div class="card-body">
                <canvas id="chartRendimiento"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-8">
        <div class="card h-100">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold"><i class="bi bi-activity text-primary me-2"></i> Actividad Reciente del Sistema</h6>
            </div>
            <div class="card-body p-0">
                <!-- Filtros -->
                <div class="bg-light p-3 border-bottom">
                    <form method="GET" action="<?php echo APP_URL; ?>/dashboard" class="row g-2 align-items-center">
                        <div class="col-auto">
                            <select name="accion" class="form-select form-select-sm">
                                <option value="">Todas las acciones</option>
                                <option value="INSERT" <?php echo ($filtros_actuales['accion'] ?? '') == 'INSERT' ? 'selected' : ''; ?>>Creación (INSERT)</option>
                                <option value="UPDATE" <?php echo ($filtros_actuales['accion'] ?? '') == 'UPDATE' ? 'selected' : ''; ?>>Edición (UPDATE)</option>
                                <option value="DELETE" <?php echo ($filtros_actuales['accion'] ?? '') == 'DELETE' ? 'selected' : ''; ?>>Eliminación (DELETE)</option>
                                <option value="LOGIN" <?php echo ($filtros_actuales['accion'] ?? '') == 'LOGIN' ? 'selected' : ''; ?>>Accesos (LOGIN)</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <select name="rol" class="form-select form-select-sm">
                                <option value="">Todos los roles</option>
                                <option value="Administrador" <?php echo ($filtros_actuales['rol'] ?? '') == 'Administrador' ? 'selected' : ''; ?>>Administrador</option>
                                <option value="Profesor" <?php echo ($filtros_actuales['rol'] ?? '') == 'Profesor' ? 'selected' : ''; ?>>Profesor</option>
                                <option value="Coordinador" <?php echo ($filtros_actuales['rol'] ?? '') == 'Coordinador' ? 'selected' : ''; ?>>Coordinador</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <select name="orden" class="form-select form-select-sm">
                                <option value="DESC" <?php echo ($filtros_actuales['orden'] ?? 'DESC') == 'DESC' ? 'selected' : ''; ?>>Más Recientes</option>
                                <option value="ASC" <?php echo ($filtros_actuales['orden'] ?? '') == 'ASC' ? 'selected' : ''; ?>>Más Antiguos</option>
                            </select>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-filter"></i> Filtrar</button>
                            <a href="<?php echo APP_URL; ?>/dashboard" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-circle"></i> Limpiar</a>
                        </div>
                    </form>
                </div>

                <!-- Tabla de Actividad -->
                <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <?php if (empty($logs)): ?>
                        <div class="p-4 text-center text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            No se encontraron registros de auditoría con los parámetros actuales.
                        </div>
                    <?php else: ?>
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th>Fecha</th>
                                    <th>Usuario</th>
                                    <th>Acción</th>
                                    <th>Módulo</th>
                                    <th>Detalles</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($logs as $log): 
                                    $badgeClass = 'bg-secondary';
                                    switch ($log['accion']) {
                                        case 'INSERT': $badgeClass = 'bg-success'; break;
                                        case 'UPDATE': $badgeClass = 'bg-warning text-dark'; break;
                                        case 'DELETE': $badgeClass = 'bg-danger'; break;
                                        case 'LOGIN':  $badgeClass = 'bg-info text-dark'; break;
                                    }
                                    
                                    $date = new DateTime($log['fecha']);
                                ?>
                                    <tr>
                                        <td class="text-nowrap text-muted small"><?php echo $date->format('d/m/Y H:i'); ?></td>
                                        <td>
                                            <span class="fw-bold d-block text-truncate" style="max-width: 150px;" title="<?php echo htmlspecialchars($log['nombre_usuario']); ?>">
                                                <?php echo htmlspecialchars($log['nombre_usuario']); ?>
                                            </span>
                                            <span class="badge bg-light text-dark border"><?php echo $log['rol_usuario']; ?></span>
                                        </td>
                                        <td><span class="badge <?php echo $badgeClass; ?>"><?php echo $log['accion']; ?></span></td>
                                        <td><span class="fw-medium"><?php echo $log['modulo']; ?></span></td>
                                        <td><small class="text-muted"><?php echo htmlspecialchars($log['detalles']); ?></small></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos para Reprobación
    const materiasRep = <?php echo json_encode(array_column($stats['avanzadas']['materias_reprobacion'], 'materia')); ?>;
    const totalRep = <?php echo json_encode(array_column($stats['avanzadas']['materias_reprobacion'], 'total_reprobados')); ?>;

    // Gráfico de Barras - Reprobación
    new Chart(document.getElementById('chartReprobacion'), {
        type: 'bar',
        data: {
            labels: materiasRep,
            datasets: [{
                label: 'Estudiantes Reprobados',
                data: totalRep,
                backgroundColor: 'rgba(220, 53, 69, 0.6)',
                borderColor: 'rgba(220, 53, 69, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });

    // Datos para Distribución
    const distData = [
        <?php echo $stats['avanzadas']['distribucion_notas']['superior'] ?? 0; ?>,
        <?php echo $stats['avanzadas']['distribucion_notas']['alto'] ?? 0; ?>,
        <?php echo $stats['avanzadas']['distribucion_notas']['basico'] ?? 0; ?>,
        <?php echo $stats['avanzadas']['distribucion_notas']['bajo'] ?? 0; ?>
    ];

    // Gráfico Circular - Rendimiento
    new Chart(document.getElementById('chartRendimiento'), {
        type: 'doughnut',
        data: {
            labels: ['Superior (4.6-5.0)', 'Alto (4.0-4.5)', 'Básico (3.0-3.9)', 'Bajo (0.0-2.9)'],
            datasets: [{
                data: distData,
                backgroundColor: [
                    'rgba(25, 135, 84, 0.7)',  // Verde (Superior)
                    'rgba(13, 110, 253, 0.7)', // Azul (Alto)
                    'rgba(255, 193, 7, 0.7)',  // Amarillo (Básico)
                    'rgba(220, 53, 69, 0.7)'   // Rojo (Bajo)
                ],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
});
</script>

<?php
$content = ob_get_clean();
include VIEWS_PATH . '/layouts/base.php';
?>
