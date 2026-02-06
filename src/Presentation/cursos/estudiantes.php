<?php
$pageTitle = 'Estudiantes del Curso';
$nombreCurso = ($curso['grado'] ?? '') . ' - ' . ($curso['seccion'] ?? '');
ob_start();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/cursos">Cursos</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $nombreCurso; ?></li>
            </ol>
        </nav>
        <h2><i class="bi bi-people-fill text-primary"></i> Estudiantes: <?php echo $nombreCurso; ?></h2>
    </div>
    <a href="<?php echo APP_URL; ?>/cursos" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver a Cursos
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="bg-light text-secondary">
                    <tr>
                        <th class="ps-4">No.</th>
                        <th>Documento</th>
                        <th>Nombre Completo</th>
                        <th>Edad</th>
                        <th>Jornada</th>
                        <th class="text-center">Estado</th>
                        <th class="text-end pe-4">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($estudiantes)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x fs-1 d-block mb-2"></i>
                                No hay estudiantes inscritos en este curso actualmente.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($estudiantes as $index => $est): ?>
                            <tr>
                                <td class="ps-4 text-muted fw-bold"><?php echo $index + 1; ?></td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        <?php echo $est->getTipoDocumento(); ?>
                                    </span> 
                                    <?php echo $est->getNumeroDocumento(); ?>
                                </td>
                                <td class="fw-bold text-dark">
                                    <?php echo $est->getApellido() . ' ' . $est->getNombre(); ?>
                                </td>
                                <td>
                                    <?php 
                                        // Calcular edad aproximada (simple)
                                        $nac = new DateTime($est->getFechaNacimiento());
                                        $hoy = new DateTime();
                                        echo $nac->diff($hoy)->y . ' años';
                                    ?>
                                </td>
                                <td><?php echo $curso['jornada']; ?></td>
                                <td class="text-center">
                                    <?php if ($est->getEstado() === 'Activo'): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success px-3">Activo</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger bg-opacity-10 text-danger px-3"><?php echo $est->getEstado(); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="<?php echo APP_URL; ?>/estudiantes/ver?id=<?php echo $est->getIdEstudiante(); ?>" 
                                           class="btn btn-sm btn-outline-primary" title="Ver Perfil">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?php echo APP_URL; ?>/reportes/boletin?estudiante_id=<?php echo $est->getIdEstudiante(); ?>&periodo=1" 
                                           class="btn btn-sm btn-outline-success" title="Ver Notas" target="_blank">
                                            <i class="bi bi-journal-text"></i>
                                        </a>
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
