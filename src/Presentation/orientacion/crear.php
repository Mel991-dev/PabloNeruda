<?php
$pageTitle = 'Nuevo Seguimiento';
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/orientacion">Orientación</a></li>
                <li class="breadcrumb-item active">Nuevo Seguimiento</li>
            </ol>
        </nav>

        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i> Registrar Intervención de Orientación</h5>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info d-flex align-items-center mb-4">
                    <i class="bi bi-person-badge fs-2 me-3"></i>
                    <div>
                        <h6 class="mb-0">Estudiante: <strong><?php echo htmlspecialchars($estudiante->getNombre() . ' ' . $estudiante->getApellido()); ?></strong></h6>
                        <small>Documento: <?php echo $estudiante->getNumeroDocumento(); ?> | Curso: <?php echo $estudiante->getNombreCurso(); ?></small>
                    </div>
                </div>

                <form action="<?php echo APP_URL; ?>/orientacion/guardar" method="POST">
                    <input type="hidden" name="id_estudiante" value="<?php echo $estudiante->getIdEstudiante(); ?>">

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tipo de Intervención</label>
                            <select name="tipo" class="form-select" required>
                                <option value="Académica">Académica</option>
                                <option value="Convivencia">Convivencia</option>
                                <option value="Socioeconómica">Socioeconómica</option>
                                <option value="Salud">Salud</option>
                                <option value="Psicológica">Psicológica</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Motivo Principal</label>
                            <input type="text" name="motivo" class="form-control" placeholder="Ej: Bajo rendimiento en matemáticas" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Descripción Detallada</label>
                        <textarea name="descripcion" class="form-control" rows="5" placeholder="Describa la situación y lo tratado en la sesión..." required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Compromisos / Acuerdos</label>
                        <textarea name="compromisos" class="form-control" rows="3" placeholder="Acciones a seguir por parte del estudiante/padres..."></textarea>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-7">
                            <label class="form-label fw-bold">Remitido a (Opcional)</label>
                            <input type="text" name="remitido_a" class="form-control" placeholder="Ej: EPS, ICBF, Comisaría de familia...">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-5">
                        <a href="javascript:history.back()" class="btn btn-secondary px-4">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-5 fw-bold">GUARDAR SEGUIMIENTO</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require_once VIEWS_PATH . '/layouts/base.php'; 
?>
