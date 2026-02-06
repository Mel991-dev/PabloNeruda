<?php
$pageTitle = 'Historial de Orientación';
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0">Hoja de Vida de Orientación</h2>
                <p class="text-muted"><?php echo htmlspecialchars($estudiante->getNombre() . ' ' . $estudiante->getApellido()); ?></p>
            </div>
            <a href="<?php echo APP_URL; ?>/orientacion/nuevo?id_estudiante=<?php echo $estudiante->getIdEstudiante(); ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Nueva Intervención
            </a>
        </div>

        <div class="row g-4">
            <!-- Resumen Informativo -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0 mb-4 sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted fw-bold small mb-3 border-bottom pb-2">Datos del Estudiante</h6>
                        <dl class="mb-0 small">
                            <dt>Curso</dt>
                            <dd><?php echo $estudiante->getNombreCurso(); ?></dd>
                            <dt>Documento</dt>
                            <dd><?php echo $estudiante->getNumeroDocumento(); ?></dd>
                            <dt>Total Intervenciones</dt>
                            <dd class="fs-5 fw-bold text-primary"><?php echo count($historial); ?></dd>
                        </dl>
                        <hr>
                        <a href="<?php echo APP_URL; ?>/estudiantes/ver?id=<?php echo $estudiante->getIdEstudiante(); ?>" class="btn btn-sm btn-outline-secondary w-100">
                            <i class="bi bi-person-vcard"></i> Ver Ficha Integral
                        </a>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="col-md-8">
                <?php if (empty($historial)): ?>
                    <div class="text-center py-5 bg-white rounded shadow-sm">
                        <i class="bi bi-calendar2-minus text-muted fs-1 mb-3 d-block"></i>
                        <h5 class="text-muted">No hay registros de seguimiento para este estudiante.</h5>
                    </div>
                <?php else: ?>
                    <div class="timeline">
                        <?php foreach ($historial as $s): ?>
                            <div class="card shadow-sm border-0 mb-4 position-relative">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
                                    <span class="badge bg-primary"><?php echo $s['tipo_intervencion']; ?></span>
                                    <span class="small text-muted fw-bold"><i class="bi bi-calendar-check me-1"></i> <?php echo date('d M, Y', strtotime($s['fecha_seguimiento'])); ?></span>
                                </div>
                                <div class="card-body">
                                    <h6 class="fw-bold mb-2"><?php echo htmlspecialchars($s['motivo']); ?></h6>
                                    <p class="mb-3 text-secondary small" style="white-space: pre-line;">
                                        <?php echo htmlspecialchars($s['descripcion']); ?>
                                    </p>
                                    
                                    <?php if ($s['compromisos']): ?>
                                        <div class="p-3 bg-warning bg-opacity-10 border-start border-3 border-warning rounded mb-3">
                                            <strong class="small d-block mb-1 text-warning-emphasis"><i class="bi bi-hand-thumbs-up"></i> Compromisos:</strong>
                                            <div class="small italic"><?php echo htmlspecialchars($s['compromisos']); ?></div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($s['remitido_a']): ?>
                                        <div class="p-2 bg-danger bg-opacity-10 border rounded mb-3">
                                            <small class="text-danger fw-bold"><i class="bi bi-forward"></i> Remitido a: <?php echo htmlspecialchars($s['remitido_a']); ?></small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="card-footer bg-white py-2 d-flex justify-content-between">
                                    <small class="text-muted">Atendido por: <strong><?php echo htmlspecialchars($s['nombre_orientador']); ?></strong></small>
                                    <span class="badge <?php echo $s['estado'] === 'Cerrado' ? 'bg-success' : 'bg-warning'; ?>"><?php echo $s['estado']; ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline { border-left: 2px solid #e9ecef; padding-left: 20px; }
    .timeline .card::before {
        content: "";
        position: absolute;
        left: -29px;
        top: 20px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #0d6efd;
        border: 4px solid #fff;
        box-shadow: 0 0 0 4px #e9ecef;
    }
</style>

<?php 
$content = ob_get_clean(); 
require_once VIEWS_PATH . '/layouts/base.php'; 
?>
