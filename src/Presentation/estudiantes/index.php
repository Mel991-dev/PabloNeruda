<?php
$pageTitle = 'Gestión de Estudiantes';
ob_start();
?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Listado de Estudiantes</h5>
        <?php if (App\Core\Session::get('rol') !== 'Orientador'): ?>
            <a href="<?php echo APP_URL; ?>/estudiantes/crear" class="btn btn-light btn-sm text-primary fw-bold">
                <i class="bi bi-person-plus-fill"></i> Nuevo Estudiante
            </a>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Documento</th>
                        <th>Nombre Completo</th>
                        <th>Edad</th>
                        <th>Curso</th>
                        <th>Estado</th>
                        <th>Alertas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($estudiantes)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                    No hay estudiantes registrados
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($estudiantes as $estudiante): ?>
                            <tr>
                                <td>
                                    <small class="text-muted"><?php echo $estudiante->getTipoDocumento(); ?></small>
                                    <br>
                                    <strong><?php echo $estudiante->getNumeroDocumento(); ?></strong>
                                </td>
                                <td>
                                    <?php echo $estudiante->getNombreCompleto(); ?>
                                </td>
                                <td>
                                    <?php echo $estudiante->calcularEdad(); ?> años
                                </td>
                                <td>
                                    <!-- TODO: Mostrar curso real -->
                                    <span class="badge bg-info">Ver Curso</span>
                                </td>
                                <td>
                                    <span class="badge bg-success"><?php echo $estudiante->getEstado(); ?></span>
                                </td>
                                <td>
                                    <?php if ($estudiante->tieneAlergias()): ?>
                                        <span class="badge bg-danger" data-bs-toggle="tooltip" title="<?php echo htmlspecialchars($estudiante->getDescripcionAlergias()); ?>">
                                            <i class="bi bi-exclamation-triangle-fill"></i> Alergias
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo APP_URL; ?>/estudiantes/ver?id=<?php echo $estudiante->getIdEstudiante(); ?>" class="btn btn-outline-primary" title="Ver Detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <?php if (App\Core\Session::get('rol') !== 'Orientador'): ?>
                                            <a href="<?php echo APP_URL; ?>/estudiantes/editar?id=<?php echo $estudiante->getIdEstudiante(); ?>" class="btn btn-outline-warning" title="Editar">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
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
