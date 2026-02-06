<?php
$pageTitle = 'Listado de Cursos';
ob_start();
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="bi bi-book-half text-primary"></i> Cursos Académicos</h2>
        <p class="text-muted">Gestión de grados y grupos escolares</p>
    </div>
    <div class="col-md-4 text-end">
        <?php if (App\Core\Session::get('rol') === 'Administrador'): ?>
        <a href="<?php echo APP_URL; ?>/cursos/crear" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Nuevo Curso
        </a>
        <?php endif; ?>
    </div>
</div>

<?php if (empty($cursosAgrupados)): ?>
    <div class="alert alert-info py-4 text-center">
        <i class="bi bi-info-circle fs-1 d-block mb-2"></i>
        No hay cursos registrados en el sistema.
    </div>
<?php else: ?>

    <div class="row">
        <?php foreach ($cursosAgrupados as $grado => $cursos): ?>
            <div class="col-12 mb-3">
                <hr>
                <h4 class="text-secondary fw-bold text-uppercase ms-2">
                    <i class="bi bi-bookmark-fill"></i> <?php echo $grado; ?>
                </h4>
            </div>

            <?php foreach ($cursos as $curso): ?>
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm border-0 border-top border-4 border-primary hover-shadow transition">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <span class="display-4 fw-bold text-dark"><?php echo $curso['seccion']; ?></span>
                            </div>
                            <h5 class="card-title text-muted mb-3">
                                Grupo <?php echo $curso['seccion']; ?>
                            </h5>
                            
                            <p class="card-text text-muted small">
                                <i class="bi bi-person-workspace"></i> 
                                Dir: <?php echo $curso['director_nombre'] ? ($curso['director_nombre'] . ' ' . $curso['director_apellido']) : 'Sin asignar'; ?>
                            </p>
                            
                            <p class="card-text fw-bold text-primary">
                                <i class="bi bi-people-fill"></i> <?php echo $curso['total_estudiantes']; ?> Estudiantes
                            </p>
                        </div>
                        <div class="card-footer bg-white border-top-0 pb-3">
                            <div class="d-grid gap-2">
                                <a href="<?php echo APP_URL; ?>/cursos/ver?id=<?php echo $curso['id_curso']; ?>" class="btn btn-outline-primary">
                                    <i class="bi bi-list-ul"></i> Ver Estudiantes
                                </a>
                                <?php if (App\Core\Session::get('rol') === 'Administrador'): ?>
                                    <a href="<?php echo APP_URL; ?>/cursos/editar?id=<?php echo $curso['id_curso']; ?>" class="btn btn-warning">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        <?php endforeach; ?>
    </div>

<?php endif; ?>

<style>
    .hover-shadow:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; transition: all 0.3s ease; }
    .transition { transition: all 0.3s ease; }
</style>

<?php 
$content = ob_get_clean(); 
require_once VIEWS_PATH . '/layouts/base.php'; 
?>
