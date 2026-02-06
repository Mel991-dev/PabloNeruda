<?php
$pageTitle = 'Nuevo Usuario';
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0"><i class="bi bi-person-plus"></i> Crear Nuevo Usuario</h5>
            </div>
            <div class="card-body p-4">
                <form action="<?php echo APP_URL; ?>/usuarios/guardar" method="POST">
                    
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Nombre de Usuario</label>
                        <input type="text" name="username" class="form-control" required autofocus>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Contraseña</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">Rol</label>
                        <select name="rol" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <option value="Administrador">Administrador</option>
                            <option value="Rector">Rector</option>
                            <option value="Coordinador">Coordinador</option>
                            <option value="Profesor">Profesor</option>
                            <option value="Orientador">Orientador</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small fw-bold">Estado Inicial</label>
                        <select name="estado" class="form-select">
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>

                    <!-- Campos Dinámicos para Profesor -->
                    <div id="campos-profesor" class="card mb-4 bg-light border-0 d-none">
                        <div class="card-body">
                            <h6 class="card-title text-primary fw-bold mb-3"><i class="bi bi-mortarboard"></i> Datos del Profesor</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small">Nombre <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" class="form-control" id="prof-nombre">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Apellido <span class="text-danger">*</span></label>
                                    <input type="text" name="apellido" class="form-control" id="prof-apellido">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Tipo Doc. <span class="text-danger">*</span></label>
                                    <select name="tipo_documento" class="form-select" id="prof-tipo">
                                        <option value="CC">Cédula de Ciudadanía</option>
                                        <option value="CE">Cédula de Extranjería</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">No. Documento <span class="text-danger">*</span></label>
                                    <input type="text" name="numero_documento" class="form-control" id="prof-doc">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Teléfono</label>
                                    <input type="text" name="telefono" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small">Especialidad</label>
                                    <input type="text" name="especialidad" class="form-control" placeholder="Ej: Matemáticas">
                                </div>
                                <div class="col-12">
                                    <label class="form-label small">Email</label>
                                    <input type="email" name="email" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        document.querySelector('select[name="rol"]').addEventListener('change', function() {
                            const camposProfesor = document.getElementById('campos-profesor');
                            const inputs = camposProfesor.querySelectorAll('input, select');
                            
                            if (this.value === 'Profesor') {
                                camposProfesor.classList.remove('d-none');
                                document.getElementById('prof-nombre').setAttribute('required', 'true');
                                document.getElementById('prof-apellido').setAttribute('required', 'true');
                                document.getElementById('prof-doc').setAttribute('required', 'true');
                            } else {
                                camposProfesor.classList.add('d-none');
                                inputs.forEach(input => input.removeAttribute('required'));
                            }
                        });
                    </script>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Crear Usuario</button>
                        <a href="<?php echo APP_URL; ?>/usuarios" class="btn btn-link text-decoration-none">Cancelar</a>
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
