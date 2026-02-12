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
