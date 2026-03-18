<?php
$pageTitle = 'Mi Horario de Clases';
ob_start();

// Definición de colores para materias (para dar variedad visual)
$colores = [
    'bg-primary text-white',
    'bg-success text-white',
    'bg-info text-dark',
    'bg-warning text-dark',
    'bg-danger text-white',
    'bg-secondary text-white',
    'bg-dark text-white'
];

// Mapa para asegurar que una materia siempre tenga el mismo color
$mapaColores = [];
$colorIndex = 0;

foreach ($horario as $dia => $bloques) {
    foreach ($bloques as $b => $celda) {
        if ($celda && !isset($mapaColores[$celda['materia_nombre']])) {
            $mapaColores[$celda['materia_nombre']] = $colores[$colorIndex % count($colores)];
            $colorIndex++;
        }
    }
}

// Mapa de horas teóricas (Ajustar según la realidad del colegio)
// Asumimos jornada única o bloque general para visualización
$mapaHoras = [
    1 => '06:00 AM - 07:00 AM',
    2 => '07:00 AM - 08:00 AM',
    3 => '08:30 AM - 09:30 AM',
    4 => '09:30 AM - 10:30 AM',
    5 => '10:30 AM - 11:30 AM',
    6 => '11:30 AM - 12:30 PM'
];
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-8">
        <h2 class="mb-1"><i class="bi bi-calendar3 text-primary me-2"></i> Mi Horario de Clases</h2>
        <p class="text-muted mb-0">Vista de carga académica semanal</p>
    </div>
    <div class="col-md-4 text-md-end mt-3 mt-md-0">
        <button class="btn btn-outline-secondary" onclick="window.print()">
            <i class="bi bi-printer"></i> Imprimir Horario
        </button>
    </div>
</div>

<div class="card shadow-sm border-0 override-print">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered text-center align-middle mb-0 custom-schedule-table">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3" style="width: 15%;"><i class="bi bi-clock"></i> Hora / Bloque</th>
                        <?php foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $dia): ?>
                            <th class="py-3" style="width: 17%;"><?php echo $dia; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($b = 1; $b <= 6; $b++): ?>
                        
                        <?php if ($b == 3): ?>
                            <!-- Representación visual del Recreo/Descanso antes del bloque 3 -->
                            <tr class="table-active">
                                <td colspan="6" class="py-2 text-muted fw-bold letter-spacing-1">
                                    <i class="bi bi-cup-hot me-2"></i> D E S C A N S O  (08:00 AM - 08:30 AM) <i class="bi bi-cup-hot ms-2"></i>
                                </td>
                            </tr>
                        <?php endif; ?>

                        <tr>
                            <td class="fw-bold bg-light align-middle border-end">
                                Bloque <?php echo $b; ?><br>
                                <small class="text-muted fw-normal" style="font-size: 0.75rem;"><?php echo $mapaHoras[$b]; ?></small>
                            </td>
                            
                            <?php foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $dia): ?>
                                <?php $celda = $horario[$dia][$b] ?? null; ?>
                                
                                <td class="p-2 align-middle" style="height: 110px; min-width: 140px;">
                                    <?php if ($celda && $celda['materia_nombre']): ?>
                                        <div class="card h-100 border-0 shadow-sm <?php echo $mapaColores[$celda['materia_nombre']]; ?>" style="border-radius: 0.5rem;">
                                            <div class="card-body p-2 d-flex flex-column justify-content-center">
                                                <h6 class="card-title fw-bold mb-1 text-truncate" title="<?php echo htmlspecialchars($celda['materia_nombre']); ?>">
                                                    <?php echo htmlspecialchars($celda['materia_nombre']); ?>
                                                </h6>
                                                <div class="d-inline-block bg-white text-dark rounded px-2 py-1 mx-auto shadow-sm mt-1" style="font-size: 0.8rem; opacity: 0.95;">
                                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i> <?php echo htmlspecialchars($celda['curso_nombre']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="h-100 d-flex align-items-center justify-content-center text-muted" style="border: 2px dashed #dee2e6; border-radius: 0.5rem; background-color: #f8f9fa;">
                                            <span class="small opacity-50"><i class="bi bi-cup text-secondary d-block fs-5 mb-1"></i>Libre</span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                
                            <?php endforeach; ?>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white text-muted text-center py-3">
        <small><i class="bi bi-info-circle me-1"></i> Si notas alguna inconsistencia con tu carga real, por favor contacta a Coordinación.</small>
    </div>
</div>

<style>
    .letter-spacing-1 { letter-spacing: 3px; }
    .custom-schedule-table td { transition: all 0.2s; }
    .custom-schedule-table td:hover { filter: brightness(0.95); }
    
    @media print {
        .navbar, .sidebar, .btn, footer { display: none !important; }
        .override-print { box-shadow: none !important; border: 1px solid #ddd !important; }
        body { padding: 0 !important; margin: 0 !important; background: white !important; }
        .main-content { margin-left: 0 !important; width: 100% !important; padding: 0 !important; }
    }
</style>

<?php 
$content = ob_get_clean(); 
require_once VIEWS_PATH . '/layouts/base.php'; 
?>
