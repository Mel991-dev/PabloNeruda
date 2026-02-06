<?php
$pageTitle = $titulo;
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Nuevo Curso</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo APP_URL; ?>/cursos/guardar" method="POST" class="needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Grado</label>
                            <select class="form-select" name="grado" required>
                                <option value="">Seleccione...</option>
                                <option value="Preescolar">Preescolar</option>
                                <option value="1°">1°</option>
                                <option value="2°">2°</option>
                                <option value="3°">3°</option>
                                <option value="4°">4°</option>
                                <option value="5°">5°</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sección / Grupo</label>
                            <select class="form-select" name="seccion" required>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Año Lectivo</label>
                            <input type="number" class="form-control" name="anio" value="<?php echo date('Y'); ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jornada</label>
                            <select class="form-select" name="jornada" required>
                                <option value="Mañana">Mañana</option>
                                <option value="Tarde">Tarde</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Capacidad Máxima</label>
                            <input type="number" class="form-control" name="capacidad" value="35" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Director de Grupo</label>
                            <select class="form-select" name="director_grupo">
                                <option value="">-- Sin asignar --</option>
                                <?php foreach ($profesores as $profesor): ?>
                                    <option value="<?php echo $profesor['id_profesor']; ?>">
                                        <?php echo htmlspecialchars($profesor['nombre'] . ' ' . $profesor['apellido']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-4 text-end">
                        <a href="<?php echo APP_URL; ?>/cursos" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Curso</button>
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
