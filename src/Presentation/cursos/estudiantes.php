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
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-success" 
                                                title="Ver Notas"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalPeriodo"
                                                data-id="<?php echo $est->getIdEstudiante(); ?>"
                                                data-nombre="<?php echo htmlspecialchars($est->getApellido() . ' ' . $est->getNombre()); ?>">
                                            <i class="bi bi-journal-text"></i>
                                        </button>
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

<!-- Modal Selección de Periodo -->
<div class="modal fade" id="modalPeriodo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-journal-check me-2"></i>Seleccionar Periodo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <p class="mb-4 text-muted">Estudiante: <span id="modalEstudianteNombre" class="fw-bold text-dark"></span></p>
                <div class="row g-3">
                    <?php for($i=1; $i<=4; $i++): ?>
                    <div class="col-6">
                        <a href="#" id="btnPeriodo<?php echo $i; ?>" target="_blank" class="btn btn-outline-primary w-100 py-3 d-flex flex-column align-items-center gap-2 hover-shadow transition-all">
                            <span class="fs-1 fw-bold"><?php echo $i; ?>°</span>
                            <span class="small text-uppercase tracking-wider">Periodo</span>
                        </a>
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalPeriodo = document.getElementById('modalPeriodo');
    if (modalPeriodo) {
        modalPeriodo.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nombre = button.getAttribute('data-nombre');
            
            document.getElementById('modalEstudianteNombre').textContent = nombre;
            
            // Actualizar enlaces
            const baseUrl = '<?php echo APP_URL; ?>/reportes/boletin';
            for(let i=1; i<=4; i++) {
                document.getElementById('btnPeriodo'+i).href = `${baseUrl}?estudiante_id=${id}&periodo=${i}`;
            }
        });
    }
});
</script>

<?php 
$content = ob_get_clean(); 
require_once VIEWS_PATH . '/layouts/base.php'; 
?>
