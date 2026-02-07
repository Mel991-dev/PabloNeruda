<?php
use App\Core\Session;

$rol = Session::get('rol', '');
?>

<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link" href="<?php echo APP_URL; ?>/dashboard">
            <i class="bi bi-house-door"></i> <span class="nav-text">Inicio</span>
        </a>
    </li>

    <?php if (in_array($rol, ['Administrador'])): ?>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo APP_URL; ?>/usuarios">
            <i class="bi bi-people"></i> <span class="nav-text">Usuarios</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo APP_URL; ?>/cursos">
            <i class="bi bi-book"></i> <span class="nav-text">Cursos</span>
        </a>
    </li>
    <?php endif; ?>

    <?php if (in_array($rol, ['Coordinador', 'Administrador'])): ?>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo APP_URL; ?>/estudiantes">
            <i class="bi bi-person-badge"></i> <span class="nav-text">Estudiantes</span>
        </a>
    </li>
    <?php endif; ?>

    <?php if (in_array($rol, ['Profesor', 'Administrador'])): ?>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo APP_URL; ?>/notas">
            <i class="bi bi-pencil-square"></i> <span class="nav-text">Registrar Notas</span>
        </a>
    </li>
    <?php endif; ?>

    <?php if (in_array($rol, ['Rector', 'Coordinador', 'Administrador'])): ?>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo APP_URL; ?>/reportes">
            <i class="bi bi-file-earmark-text"></i> <span class="nav-text">Reportes</span>
        </a>
    </li>
    <?php endif; ?>

    <li class="nav-item mt-4 separator-item">
        <hr class="bg-white">
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo APP_URL; ?>/logout">
            <i class="bi bi-box-arrow-right"></i> <span class="nav-text">Cerrar Sesión</span>
        </a>
    </li>
</ul>
