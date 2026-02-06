<?php
$pageTitle = 'Gestión de Notas';
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white text-center py-4">
                <i class="bi bi-journal-check display-1"></i>
                <h4 class="mt-3">Gestión de Calificaciones</h4>
                <p class="mb-0 text-white-50">Seleccione curso y materia para continuar</p>
            </div>
            
            <div class="card-body p-4">
                <form action="<?php echo APP_URL; ?>/notas/registrar" method="GET">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Curso / Grado</label>
                        <select class="form-select form-select-lg" name="curso" required>
                            <option value="">Seleccione un curso...</option>
                            <?php foreach ($cursos as $curso): ?>
                                <option value="<?php echo $curso['id_curso']; ?>">
                                    <?php echo $curso['grado'] . ' - ' . $curso['seccion']; ?> 
                                    (<?php echo $curso['año_lectivo']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Materia / Asignatura</label>
                        <select class="form-select form-select-lg" name="materia" required>
                            <option value="">Seleccione una materia...</option>
                            <?php foreach ($materias as $materia): ?>
                                <option value="<?php echo $materia->getId(); ?>">
                                    <?php echo $materia->getNombre(); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold">Periodo Académico</label>
                        <select class="form-select" name="periodo" required>
                            <option value="1">Periodo 1</option>
                            <option value="2">Periodo 2</option>
                            <option value="3">Periodo 3</option>
                            <option value="4">Periodo 4</option>
                        </select>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-search"></i> Consultar y Registrar Notas
                        </button>
                    </div>

                </form>
            </div>
            <div class="card-footer text-center text-muted bg-light">
                <small><i class="bi bi-info-circle"></i> Solo podrás ver los cursos asignados</small>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require_once VIEWS_PATH . '/layouts/base.php'; 
?>
