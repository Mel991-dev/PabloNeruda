<?php
$pageTitle = 'Horario de Clases - Grado ' . htmlspecialchars($curso['grado'] . ' ' . $curso['seccion']);
// Verificar si el horario está completamente vacío
$horarioVacio = true;
foreach ($horario as $dia => $bloques) {
    foreach ($bloques as $bloque) {
        if ($bloque !== null) {
            $horarioVacio = false;
            break 2;
        }
    }
}
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2><i class="bi bi-calendar-grid-3x3 text-info"></i> Horario de Clases</h2>
        <p class="text-muted fs-5">
            <strong>Curso:</strong> <?php echo htmlspecialchars($curso['grado'] . ' ' . $curso['seccion']); ?> | 
            <strong>Jornada:</strong> <?php echo htmlspecialchars($curso['jornada']); ?> | 
            <strong>Director:</strong> 
            <span class="badge bg-secondary">
            <?php 
                // Encontrar el nombre del director actual si existe
                $directorNombre = 'Sin asignar';
                if ($curso['director_grupo']) {
                    foreach($profesores as $p) {
                        if($p['id_profesor'] == $curso['director_grupo']) {
                            $directorNombre = $p['nombre_completo'];
                            break;
                        }
                    }
                }
                echo htmlspecialchars($directorNombre); 
            ?>
            </span>
        </p>
    </div>
    <a href="<?php echo APP_URL; ?>/cursos" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver a Cursos
    </a>
</div>

<?php if ($horarioVacio): ?>
<div class="alert alert-warning shadow-sm border-start border-warning border-4 py-3 mb-4" role="alert">
    <div class="d-flex align-items-center">
        <i class="bi bi-exclamation-triangle-fill fs-3 text-warning me-3"></i>
        <div>
            <h5 class="alert-heading mb-1 fw-bold">Horario no configurado</h5>
            <?php if ($canEdit): ?>
                <p class="mb-0">Aún no se han asignado materias a la programación semanal de este curso. Utiliza la cuadrícula inferior para comenzar a estructurar el horario escolar.</p>
            <?php else: ?>
                <p class="mb-0">Aún no se han asignado materias a la programación semanal de este curso. Solo el director de grupo y personal administrativo pueden configurar este horario.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered table-hover mb-0 text-center align-middle schedule-table">
                <thead class="table-light">
                    <tr>
                        <th class="bg-primary text-white py-3" style="width: 10%;">Bloque</th>
                        <?php foreach (array_keys($horario) as $dia): ?>
                            <th class="bg-primary text-white py-3 fw-bold fs-5" style="width: 18%;"><?php echo $dia; ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Definir los horarios reales para cada bloque según la jornada
                    $horariosReales = [
                        'Mañana' => [
                            1 => '6:00 AM - 7:00 AM',
                            2 => '7:00 AM - 8:00 AM',
                            // Recreo 8:00 AM - 8:30 AM
                            3 => '8:30 AM - 9:30 AM',
                            4 => '9:30 AM - 10:30 AM',
                            5 => '10:30 AM - 11:30 AM',
                            6 => '11:30 AM - 12:30 PM'
                        ],
                        'Tarde' => [
                            1 => '12:30 PM - 1:30 PM',
                            2 => '1:30 PM - 2:30 PM',
                            // Recreo 2:30 PM - 3:00 PM
                            3 => '3:00 PM - 4:00 PM',
                            4 => '4:00 PM - 5:00 PM',
                            5 => '5:00 PM - 6:00 PM',
                            6 => '6:00 PM - 7:00 PM'
                        ]
                    ];
                    $jornada = $curso['jornada'] ?? 'Mañana';
                    // Si por alguna razón la jornada no es Mañana o Tarde, usar Mañana por defecto
                    $tiempos = isset($horariosReales[$jornada]) ? $horariosReales[$jornada] : $horariosReales['Mañana'];

                    for ($b = 1; $b <= 6; $b++): 
                    ?>
                        <tr>
                            <td class="fw-bold bg-light">
                                <span class="d-block text-primary">Bloque <?php echo $b; ?></span>
                                <small class="text-muted" style="font-size: 0.8em;"><?php echo $tiempos[$b]; ?></small>
                            </td>
                            <?php foreach ($horario as $dia => $bloques): 
                                $celda = $bloques[$b]; 
                                $materiaAsignada = $celda ? $celda['fk_materia'] : '';
                                $profesorAsignado = $celda ? $celda['fk_profesor'] : '';
                            ?>
                                <td class="p-2 schedule-cell" data-dia="<?php echo $dia; ?>" data-bloque="<?php echo $b; ?>">
                                    <!-- Vista Lectura / Edición Rápida -->
                                    <div class="cell-content">
                                        <select class="form-select form-select-sm mb-1 materia-select shadow-sm border-info" data-dia="<?php echo $dia; ?>" data-bloque="<?php echo $b; ?>" <?php echo !$canEdit ? 'disabled' : ''; ?>>
                                            <option value="">-- Asignatura --</option>
                                            <?php foreach ($materias as $m): ?>
                                                <option value="<?php echo $m['id_materia']; ?>" <?php echo ($materiaAsignada == $m['id_materia']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($m['nombre']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        
                                        <select class="form-select form-select-sm profesor-select text-muted" data-dia="<?php echo $dia; ?>" data-bloque="<?php echo $b; ?>" <?php echo !$canEdit ? 'disabled' : ''; ?>>
                                            <option value="">-- Docente --</option>
                                            <?php foreach ($profesores as $p): ?>
                                                <option value="<?php echo $p['id_profesor']; ?>" <?php echo ($profesorAsignado == $p['id_profesor'] || (!$profesorAsignado && $p['id_profesor'] == $curso['director_grupo'])) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($p['nombre_completo']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="saving-indicator text-success small mt-1 visually-hidden">
                                        <i class="bi bi-check2-circle"></i> Guardado
                                    </div>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                        <?php 
                        // El recreo es después del bloque 2
                        if ($b == 2): 
                            $textoRecreo = ($jornada == 'Mañana') ? '8:00 AM - 8:30 AM' : '2:30 PM - 3:00 PM';
                        ?>
                            <tr class="table-active">
                                <td colspan="6" class="text-center fw-bold text-muted py-2">
                                    <i class="bi bi-cup-hot me-2"></i>DESCANSO (RECREO) <span class="badge bg-secondary ms-2"><?php echo $textoRecreo; ?></span>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .schedule-table th, .schedule-table td { vertical-align: middle; }
    .schedule-cell { transition: background-color 0.2s; }
    .schedule-cell:hover { background-color: #f8f9fa; }
    .materia-select { font-weight: bold; color: #0d6efd; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selects = document.querySelectorAll('.materia-select, .profesor-select');
    const cursoId = <?php echo $curso['id_curso']; ?>;

    selects.forEach(select => {
        select.addEventListener('change', function() {
            const td = this.closest('td');
            const dia = td.dataset.dia;
            const bloque = td.dataset.bloque;
            const materiaId = td.querySelector('.materia-select').value;
            const profesorId = td.querySelector('.profesor-select').value;
            const indicator = td.querySelector('.saving-indicator');

            // Preparar datos
            const data = {
                curso_id: cursoId,
                dia: dia,
                bloque: parseInt(bloque),
                materia_id: materiaId ? parseInt(materiaId) : null,
                profesor_id: profesorId ? parseInt(profesorId) : null
            };

            // Mostrar estado de carga opcional aquí

            fetch('<?php echo APP_URL; ?>/horarios/guardar', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if(result.success) {
                    indicator.classList.remove('text-danger', 'visually-hidden');
                    indicator.classList.add('text-success');
                    indicator.innerHTML = '<i class="bi bi-check2-circle"></i> Guardado';
                    setTimeout(() => { indicator.classList.add('visually-hidden'); }, 2000);
                } else {
                    indicator.classList.remove('text-success', 'visually-hidden');
                    indicator.classList.add('text-danger');
                    indicator.innerHTML = '<i class="bi bi-x-circle"></i> Error';
                    alert('Error al guardar: ' + (result.message || 'Error desconocido'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                indicator.classList.remove('text-success', 'visually-hidden');
                indicator.classList.add('text-danger');
                indicator.innerHTML = '<i class="bi bi-wifi-off"></i> Fallo red';
            });
        });
    });
});
</script>

<?php 
$content = ob_get_clean(); 
require_once VIEWS_PATH . '/layouts/base.php'; 
?>
