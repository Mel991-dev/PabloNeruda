<?php
$pageTitle = $titulo;
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Curso</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo APP_URL; ?>/cursos/actualizar" method="POST" class="needs-validation" novalidate>
                    <input type="hidden" name="id" value="<?php echo $curso['id_curso']; ?>">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Grado</label>
                            <select class="form-select" name="grado" required>
                                <option value="">Seleccione...</option>
                                <?php 
                                $grados = ['Preescolar', '1°', '2°', '3°', '4°', '5°'];
                                foreach ($grados as $grado): 
                                    $selected = ($curso['grado'] == $grado) ? 'selected' : '';
                                ?>
                                    <option value="<?php echo $grado; ?>" <?php echo $selected; ?>><?php echo $grado; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sección / Grupo</label>
                            <select class="form-select" name="seccion" required>
                                <?php 
                                $secciones = ['A', 'B', 'C'];
                                foreach ($secciones as $seccion):
                                    $selected = ($curso['seccion'] == $seccion) ? 'selected' : '';
                                ?>
                                    <option value="<?php echo $seccion; ?>" <?php echo $selected; ?>><?php echo $seccion; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Año Lectivo</label>
                            <input type="number" class="form-control" name="anio" value="<?php echo $curso['año_lectivo']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Jornada</label>
                            <select class="form-select" name="jornada" required>
                                <?php 
                                $jornadas = ['Mañana', 'Tarde'];
                                foreach ($jornadas as $jor):
                                    $selected = ($curso['jornada'] == $jor) ? 'selected' : '';
                                ?>
                                    <option value="<?php echo $jor; ?>" <?php echo $selected; ?>><?php echo $jor; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Capacidad Máxima</label>
                            <input type="number" class="form-control" name="capacidad" value="<?php echo $curso['capacidad_maxima']; ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Director de Grupo</label>
                            <select class="form-select" name="director_grupo">
                                <option value="">-- Sin asignar --</option>
                                <?php foreach ($profesores as $profesor): 
                                    $selected = ($curso['director_grupo'] == $profesor['id_profesor']) ? 'selected' : '';
                                ?>
                                    <option value="<?php echo $profesor['id_profesor']; ?>" <?php echo $selected; ?>>
                                        <?php echo htmlspecialchars($profesor['nombre'] . ' ' . $profesor['apellido']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-4 text-end">
                        <a href="<?php echo APP_URL; ?>/cursos" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar Curso</button>
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
