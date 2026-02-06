<?php
$pageTitle = 'Generar Reportes';
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-success text-white py-3">
                <div class="d-flex align-items-center">
                    <i class="bi bi-printer-fill fs-3 me-3"></i>
                    <div>
                        <h5 class="mb-0">Generador de Boletines</h5>
                        <small>Impresión de calificaciones por periodo</small>
                    </div>
                </div>
            </div>
            
            <div class="card-body p-4">
                <form action="<?php echo APP_URL; ?>/reportes/boletin" method="GET" target="_blank">
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">1. Seleccionar Grado/Curso</label>
                            <select class="form-select" id="selectCurso" required>
                                <option value="">Seleccione...</option>
                                <?php foreach ($cursos as $curso): ?>
                                    <option value="<?php echo $curso['id_curso']; ?>">
                                        <?php echo $curso['grado'] . ' - ' . $curso['seccion']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label fw-bold">2. Periodo Académico</label>
                            <select class="form-select" name="periodo" required>
                                <option value="1">Periodo 1</option>
                                <option value="2">Periodo 2</option>
                                <option value="3">Periodo 3</option>
                                <option value="4">Periodo 4</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">3. Seleccionar Estudiante</label>
                        <select class="form-select" name="estudiante_id" id="selectEstudiante" required disabled>
                            <option value="">Primero seleccione un curso...</option>
                        </select>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg" id="btnGenerar" disabled>
                            <i class="bi bi-file-earmark-pdf"></i> Generar Boletín
                        </button>
                    </div>

                </form>
            </div>
        </div>
        
        <div class="alert alert-info mt-4">
            <i class="bi bi-info-circle-fill"></i> El boletín se abrirá en una nueva pestaña listo para imprimir o guardar como PDF.
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectCurso = document.getElementById('selectCurso');
    const selectEstudiante = document.getElementById('selectEstudiante');
    const btnGenerar = document.getElementById('btnGenerar');

    selectCurso.addEventListener('change', function() {
        const cursoId = this.value;
        
        // Reset
        selectEstudiante.innerHTML = '<option value="">Cargando estudiantes...</option>';
        selectEstudiante.disabled = true;
        btnGenerar.disabled = true;

        if (!cursoId) {
            selectEstudiante.innerHTML = '<option value="">Primero seleccione un curso...</option>';
            return;
        }

        // Fetch API para cargar estudiantes dinámicamente
        fetch(`<?php echo APP_URL; ?>/api/estudiantes?curso_id=${cursoId}`)
            .then(response => response.json())
            .then(data => {
                selectEstudiante.innerHTML = '<option value="">Seleccione un estudiante...</option>';
                
                if (data.length > 0) {
                    data.forEach(est => {
                        const option = document.createElement('option');
                        option.value = est.id;
                        option.textContent = est.documento + ' - ' + est.nombre;
                        selectEstudiante.appendChild(option);
                    });
                    selectEstudiante.disabled = false;
                } else {
                    selectEstudiante.innerHTML = '<option value="">No hay estudiantes en este curso</option>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                selectEstudiante.innerHTML = '<option value="">Error al cargar datos</option>';
            });
    });

    selectEstudiante.addEventListener('change', function() {
        btnGenerar.disabled = !this.value;
    });
});
</script>

<?php 
$content = ob_get_clean(); 
require_once VIEWS_PATH . '/layouts/base.php'; 
?>
