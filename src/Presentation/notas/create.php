<?php
$pageTitle = 'Registrar Notas';
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1"><i class="bi bi-pencil-square text-primary"></i> Registrar Calificaciones</h4>
        <p class="text-muted mb-0">
            <strong>Curso:</strong> <?php echo $nombreCurso; ?> &bull;
            <strong>Materia:</strong> <?php echo $nombreMateria; ?> &bull;
            <strong>Periodo:</strong> <?php echo $periodo; ?>
        </p>
    </div>
    <a href="<?php echo APP_URL; ?>/notas" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Cambiar Selección
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <form action="<?php echo APP_URL; ?>/notas/guardar" method="POST" id="formNotas">
            <input type="hidden" name="curso_id" value="<?php echo $cursoId; ?>">
            <input type="hidden" name="materia_id" value="<?php echo $materiaId; ?>">
            <input type="hidden" name="periodo" value="<?php echo $periodo; ?>">

            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="bg-light text-center align-middle sticky-top">
                        <tr>
                            <th class="text-start ps-3" style="width: 25%;">Estudiante</th>
                            <th style="width: 10%;">Nota 1</th>
                            <th style="width: 10%;">Nota 2</th>
                            <th style="width: 10%;">Nota 3</th>
                            <th style="width: 10%;">Nota 4</th>
                            <th style="width: 10%;">Nota 5</th>
                            <th style="width: 10%;">Promedio</th>
                            <th style="width: 15%;">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($estudiantes)): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-1 d-block mb-2"></i>
                                    No hay estudiantes matriculados en este curso y materia.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($estudiantes as $est): ?>
                                <?php 
                                    $idMat = $est['id_matricula'];
                                    // Valores por defecto
                                    $n1 = $est['nota1'] ?? '';
                                    $n2 = $est['nota2'] ?? '';
                                    $n3 = $est['nota3'] ?? '';
                                    $n4 = $est['nota4'] ?? '';
                                    $n5 = $est['nota5'] ?? '';
                                    $prom = $est['promedio'] ?? 0;
                                    $estado = $est['estado'] ?? '-';
                                    $bgEstado = match($estado) {
                                        'Aprobado' => 'bg-success',
                                        'Reprobado' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                ?>
                                <tr>
                                    <td class="ps-3">
                                        <strong><?php echo $est['apellido'] . ' ' . $est['nombre']; ?></strong><br>
                                        <small class="text-muted"><?php echo $est['numero_documento']; ?></small>
                                    </td>
                                    <td>
                                        <input type="number" step="0.1" min="0" max="5" 
                                            class="form-control form-control-sm text-center input-nota" 
                                            name="notas[<?php echo $idMat; ?>][1]" 
                                            value="<?php echo $n1; ?>">
                                    </td>
                                    <td>
                                        <input type="number" step="0.1" min="0" max="5" 
                                            class="form-control form-control-sm text-center input-nota" 
                                            name="notas[<?php echo $idMat; ?>][2]" 
                                            value="<?php echo $n2; ?>">
                                    </td>
                                    <td>
                                        <input type="number" step="0.1" min="0" max="5" 
                                            class="form-control form-control-sm text-center input-nota" 
                                            name="notas[<?php echo $idMat; ?>][3]" 
                                            value="<?php echo $n3; ?>">
                                    </td>
                                    <td>
                                        <input type="number" step="0.1" min="0" max="5" 
                                            class="form-control form-control-sm text-center input-nota" 
                                            name="notas[<?php echo $idMat; ?>][4]" 
                                            value="<?php echo $n4; ?>">
                                    </td>
                                    <td>
                                        <input type="number" step="0.1" min="0" max="5" 
                                            class="form-control form-control-sm text-center input-nota" 
                                            name="notas[<?php echo $idMat; ?>][5]" 
                                            value="<?php echo $n5; ?>">
                                    </td>
                                    <td class="text-center fw-bold fs-5 text-primary">
                                        <?php echo number_format((float)$prom, 1); ?>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm" 
                                            name="notas[<?php echo $idMat; ?>][observaciones]" 
                                            value="<?php echo htmlspecialchars($est['observaciones'] ?? ''); ?>"
                                            placeholder="Opcional...">
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php if (!empty($estudiantes)): ?>
            <div class="card-footer bg-white p-3 text-end sticky-bottom shadow-lg">
                <button type="reset" class="btn btn-secondary me-2">Restablecer</button>
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-save"></i> Guardar Cambios
                </button>
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<script>
// Validación simple en cliente para rango 0.0 - 5.0
document.querySelectorAll('.input-nota').forEach(input => {
    input.addEventListener('change', function() {
        let val = parseFloat(this.value);
        if (val < 0) this.value = 0;
        if (val > 5) this.value = 5;
    });
});
</script>

<?php 
$content = ob_get_clean(); 
require_once VIEWS_PATH . '/layouts/base.php'; 
?>
