<?php
use App\Core\Session;

$rol = Session::get('rol', '');
?>

<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link" href="<?php echo APP_URL; ?>/dashboard">
            <i class="bi bi-house-door"></i> Inicio
        </a>
    </li>

    <?php if (in_array($rol, ['Administrador'])): ?>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo APP_URL; ?>/usuarios">
            <i class="bi bi-people"></i> Usuarios
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo APP_URL; ?>/cursos">
            <i class="bi bi-book"></i> Cursos
        </a>
    </li>
    <?php endif; ?>

    <?php if (in_array($rol, ['Coordinador', 'Administrador'])): ?>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo APP_URL; ?>/estudiantes">
            <i class="bi bi-person-badge"></i> Estudiantes
        </a>
    </li>
    <?php endif; ?>

    <?php if (in_array($rol, ['Profesor', 'Administrador'])): ?>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo APP_URL; ?>/notas">
            <i class="bi bi-clipboard-check"></i> Mis Notas
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo APP_URL; ?>/notas/registrar">
            <i class="bi bi-pencil-square"></i> Registrar Notas
        </a>
    </li>
    <?php endif; ?>

    <?php if (in_array($rol, ['Rector', 'Coordinador', 'Administrador'])): ?>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo APP_URL; ?>/reportes">
            <i class="bi bi-file-earmark-text"></i> Reportes
        </a>
    </li>
    <?php endif; ?>

    <li class="nav-item mt-4">
        <hr class="bg-white">
    </li>
    <li class="nav-item">
        <a class="nav-link" href="<?php echo APP_URL; ?>/logout">
            <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
        </a>
    </li>
</ul>
