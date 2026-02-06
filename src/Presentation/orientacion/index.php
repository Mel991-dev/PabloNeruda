<?php
$pageTitle = 'Historial de Intervenciones';
ob_start();
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-6">
        <h2 class="fw-bold mb-0"><i class="bi bi-clipboard-pulse text-primary"></i> Seguimientos de Orientación</h2>
        <p class="text-muted">Listado general de intervenciones registradas.</p>
    </div>
    <div class="col-md-6 text-md-end">
        <a href="<?php echo APP_URL; ?>/orientacion" class="btn btn-outline-danger me-2">
            <i class="bi bi-shield-exclamation"></i> Ver Alertas
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Fecha</th>
                        <th>Estudiante</th>
                        <th>Tipo</th>
                        <th>Motivo</th>
                        <th>Orientador</th>
                        <th>Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($seguimientos)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="bi bi-inbox text-muted fs-1 mb-2 d-block"></i>
                                <p class="text-muted">No se han registrado seguimientos aún.</p>
                                <a href="<?php echo APP_URL; ?>/estudiantes" class="btn btn-sm btn-primary">Buscar Estudiante</a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($seguimientos as $s): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold small"><?php echo date('d/m/Y', strtotime($s['fecha_seguimiento'])); ?></div>
                                    <small class="text-muted"><?php echo date('H:i', strtotime($s['fecha_seguimiento'])); ?></small>
                                </td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($s['nombre'] . ' ' . $s['apellido']); ?></div>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?php echo $s['tipo_intervencion']; ?></span></td>
                                <td><div class="text-truncate" style="max-width: 150px;"><?php echo htmlspecialchars($s['motivo']); ?></div></td>
                                <td><small><?php echo htmlspecialchars($s['orientador']); ?></small></td>
                                <td>
                                    <?php 
                                    $badgeClass = match($s['estado']) {
                                        'Abierto' => 'bg-warning',
                                        'En Proceso' => 'bg-info',
                                        'Cerrado' => 'bg-success',
                                        default => 'bg-secondary'
                                    };
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>"><?php echo $s['estado']; ?></span>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?php echo APP_URL; ?>/orientacion/historial?id=<?php echo $s['fk_estudiante']; ?>" class="btn btn-sm btn-outline-primary" title="Ver Historial Completo">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require_once VIEWS_PATH . '/layouts/base.php'; 
?>
