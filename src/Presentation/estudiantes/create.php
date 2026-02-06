<?php
$pageTitle = 'Registrar Estudiante';
ob_start();
?>

<div class="row justify-content-center">
    <div class="col-lg-11">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-person-plus-fill"></i> Ficha de Admisión Estudiantil V2.0</h5>
                <span class="badge bg-light text-primary">Modo: Inscripción Integral</span>
            </div>
            <div class="card-body p-0">
                <form action="<?php echo APP_URL; ?>/estudiantes/guardar" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate id="formEstudiante">
                    
                    <!-- Navegación de Pestañas -->
                    <ul class="nav nav-tabs nav-justified bg-light" id="admissionTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active py-3 fw-bold" id="basicos-tab" data-bs-toggle="tab" data-bs-target="#basicos" type="button" role="tab">
                                <i class="bi bi-1-circle-fill me-1"></i> Datos Básicos
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 fw-bold" id="salud-tab" data-bs-toggle="tab" data-bs-target="#salud" type="button" role="tab">
                                <i class="bi bi-2-circle-fill me-1"></i> Salud y Socioeconómico
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 fw-bold" id="familia-tab" data-bs-toggle="tab" data-bs-target="#familia" type="button" role="tab">
                                <i class="bi bi-3-circle-fill me-1"></i> Núcleo Familiar
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 fw-bold" id="acudiente-tab" data-bs-toggle="tab" data-bs-target="#acudiente" type="button" role="tab">
                                <i class="bi bi-4-circle-fill me-1"></i> Acudiente y Académico
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content p-4" id="admissionTabsContent">
                        
                        <!-- TAB 1: DATOS BÁSICOS -->
                        <div class="tab-pane fade show active" id="basicos" role="tabpanel">
                            <h6 class="text-primary border-bottom pb-2 mb-3">Información de Identidad</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Nombres <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" class="form-control" placeholder="Ej: Juan Gabriel" required border>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Apellidos <span class="text-danger">*</span></label>
                                    <input type="text" name="apellido" class="form-control" placeholder="Ej: Pérez Rodríguez" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                    <input type="date" name="fecha_nacimiento" class="form-control" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Tipo Documento <span class="text-danger">*</span></label>
                                    <select name="tipo_documento" class="form-select" required>
                                        <option value="">Seleccione...</option>
                                        <option value="RC">Registro Civil</option>
                                        <option value="TI">Tarjeta de Identidad</option>
                                        <option value="CE">Cédula de Extranjería</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Número Documento <span class="text-danger">*</span></label>
                                    <input type="text" name="numero_documento" class="form-control" required placeholder="Sin puntos ni comas">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Lugar de Nacimiento</label>
                                    <input type="text" name="lugar_nacimiento" class="form-control" placeholder="Ciudad/Municipio">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Número de Hermanos</label>
                                    <input type="number" name="numero_hermanos" class="form-control" min="0" value="0">
                                </div>
                            </div>

                            <h6 class="text-primary border-bottom pb-2 mb-3">Información de Matrícula</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Grado a Matricular <span class="text-danger">*</span></label>
                                    <select name="curso" class="form-select" required>
                                        <option value="">Seleccione el curso...</option>
                                        <?php foreach ($cursos as $curso): ?>
                                            <option value="<?php echo $curso['id_curso']; ?>">
                                                <?php echo htmlspecialchars($curso['grado'] . ' - ' . $curso['seccion']); ?> 
                                                (Cupos: <?php echo $curso['cupos_disponibles']; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Institución de Procedencia</label>
                                    <input type="text" name="procedencia" class="form-control" placeholder="Colegio anterior">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Documento PDF (RC/TI)</label>
                                    <input type="file" name="documento_pdf" class="form-control" accept=".pdf">
                                    <small class="text-muted">Formato PDF, máx 2MB</small>
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <button type="button" class="btn btn-primary px-4 btn-next-tab">Siguiente <i class="bi bi-arrow-right"></i></button>
                            </div>
                        </div>

                        <!-- TAB 2: SALUD Y SOCIOECONÓMICO -->
                        <div class="tab-pane fade" id="salud" role="tabpanel">
                            <div class="row">
                                <!-- Columna Salud -->
                                <div class="col-md-6 border-end">
                                    <h6 class="text-danger border-bottom pb-2 mb-3"><i class="bi bi-heart-pulse"></i> Información Médica</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">EPS</label>
                                            <input type="text" name="eps" class="form-control" placeholder="Ej: Sura, Sanitas">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Grupo Sanguíneo</label>
                                            <select name="tipo_sangre" class="form-select">
                                                <option value="">Seleccione...</option>
                                                <option value="O+">O+</option>
                                                <option value="O-">O-</option>
                                                <option value="A+">A+</option>
                                                <option value="A-">A-</option>
                                                <option value="B+">B+</option>
                                                <option value="B-">B-</option>
                                                <option value="AB+">AB+</option>
                                                <option value="AB-">AB-</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-bold">Limitaciones / Discapacidad</label>
                                            <input type="text" name="lim_fisicas" class="form-control" placeholder="Describa si aplica">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mt-2">
                                                <input class="form-check-input" type="checkbox" name="vacunas" value="1" id="vacunasCheck" checked>
                                                <label class="form-check-label small fw-bold" for="vacunasCheck">Esquema vacunas completo</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch mt-2">
                                                <input class="form-check-input" type="checkbox" name="toma_meds" value="1" id="medsCheck">
                                                <label class="form-check-label small fw-bold" for="medsCheck">Toma medicamentos</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" name="tiene_alergias" value="1" id="alergiasCheck">
                                                <label class="form-check-label small fw-bold" for="alergiasCheck">¿Tiene Alergias?</label>
                                            </div>
                                            <textarea name="descripcion_alergias" id="alergiasTab2" class="form-control d-none" rows="2" placeholder="Describa las alergias..."></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Columna Socioeconómica -->
                                <div class="col-md-6">
                                    <h6 class="text-success border-bottom pb-2 mb-3"><i class="bi bi-house-door"></i> Información Socioeconómica</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Nivel Sisben</label>
                                            <input type="text" name="sisben" class="form-control" placeholder="A1, B2, etc.">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Estrato</label>
                                            <select name="estrato" class="form-select">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4+</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Barrio</label>
                                            <input type="text" name="barrio" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Sector</label>
                                            <select name="sector" class="form-select">
                                                <option value="Urbano">Urbano</option>
                                                <option value="Rural">Rural</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="internet" value="1">
                                                <label class="form-check-label small">Tiene Internet</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="desplazado" value="1">
                                                <label class="form-check-label small">Desplazado</label>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-bold">Grupo Étnico</label>
                                            <input type="text" name="etnia" class="form-control" placeholder="Si aplica">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary btn-prev-tab"><i class="bi bi-arrow-left"></i> Anterior</button>
                                <button type="button" class="btn btn-primary px-4 btn-next-tab">Siguiente <i class="bi bi-arrow-right"></i></button>
                            </div>
                        </div>

                        <!-- TAB 3: NÚCLEO FAMILIAR -->
                        <div class="tab-pane fade" id="familia" role="tabpanel">
                            <div class="row">
                                <!-- Datos del Padre -->
                                <div class="col-md-6 border-end">
                                    <h6 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-gender-male"></i> Datos del Padre</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Nombres</label>
                                            <input type="text" name="padre_nombre" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Apellidos</label>
                                            <input type="text" name="padre_apellido" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Identificación</label>
                                            <input type="text" name="padre_num_doc" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Teléfono</label>
                                            <input type="text" name="padre_tel" class="form-control">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-bold">Empresa / Ocupación</label>
                                            <input type="text" name="padre_empresa" class="form-control">
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="padre_vive" value="1" id="padreVive">
                                                <label class="form-check-label small" for="padreVive text-muted">¿Vive con el estudiante?</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Datos de la Madre -->
                                <div class="col-md-6">
                                    <h6 class="text-primary border-bottom pb-2 mb-3"><i class="bi bi-gender-female"></i> Datos de la Madre</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Nombres</label>
                                            <input type="text" name="madre_nombre" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Apellidos</label>
                                            <input type="text" name="madre_apellido" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Identificación</label>
                                            <input type="text" name="madre_num_doc" class="form-control">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Teléfono</label>
                                            <input type="text" name="madre_tel" class="form-control">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-bold">Empresa / Ocupación</label>
                                            <input type="text" name="madre_empresa" class="form-control">
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="madre_vive" value="1" id="madreVive">
                                                <label class="form-check-label small" for="madreVive">¿Vive con el estudiante?</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary btn-prev-tab"><i class="bi bi-arrow-left"></i> Anterior</button>
                                <button type="button" class="btn btn-primary px-4 btn-next-tab">Siguiente <i class="bi bi-arrow-right"></i></button>
                            </div>
                        </div>

                        <!-- TAB 4: ACUDIENTE Y ACADÉMICO -->
                        <div class="tab-pane fade" id="acudiente" role="tabpanel">
                            <div class="row">
                                <!-- Columna Acudiente -->
                                <div class="col-md-8 border-end">
                                    <h6 class="text-secondary border-bottom pb-2 mb-3"><i class="bi bi-person-check"></i> Acudiente (Contacto de Emergencia)</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Nombres <span class="text-danger">*</span></label>
                                            <input type="text" name="acudiente_nombre" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Apellidos <span class="text-danger">*</span></label>
                                            <input type="text" name="acudiente_apellido" class="form-control" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Parentesco <span class="text-danger">*</span></label>
                                            <select name="acudiente_parentesco" class="form-select" required>
                                                <option value="Padre">Padre</option>
                                                <option value="Madre">Madre</option>
                                                <option value="Abuelo/a">Abuelo/a</option>
                                                <option value="Tío/a">Tío/a</option>
                                                <option value="Hermano/a">Hermano/a</option>
                                                <option value="Otro">Otro</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Teléfono Principal <span class="text-danger">*</span></label>
                                            <input type="text" name="acudiente_telefono" class="form-control" required>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-bold">Dirección</label>
                                            <input type="text" name="acudiente_direccion" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Columna Académico -->
                                <div class="col-md-4">
                                    <h6 class="text-info border-bottom pb-2 mb-3"><i class="bi bi-journal-check"></i> Historial</h6>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label small fw-bold">Última Institución</label>
                                            <input type="text" name="ant_institucion" class="form-control">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-bold">Último Grado Cursado</label>
                                            <input type="text" name="ant_nivel" class="form-control">
                                        </div>
                                        <div class="col-12 text-center py-4">
                                            <i class="bi bi-shield-check text-success display-4"></i>
                                            <p class="small text-muted mt-2">Verifique que todos los datos sean correctos antes de finalizar.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary btn-prev-tab"><i class="bi bi-arrow-left"></i> Anterior</button>
                                <button type="submit" class="btn btn-success px-5 fw-bold"><i class="bi bi-cloud-arrow-up"></i> FINALIZAR INSCRIPCIÓN</button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('#admissionTabs button');
        const nextBtns = document.querySelectorAll('.btn-next-tab');
        const prevBtns = document.querySelectorAll('.btn-prev-tab');
        const form = document.getElementById('formEstudiante');

        // Función para cambiar de pestaña con validación visual
        function goToTab(index) {
            const nextTab = new bootstrap.Tab(tabs[index]);
            nextTab.show();
            window.scrollTo(0, 0);
        }

        nextBtns.forEach((btn, idx) => {
            btn.addEventListener('click', () => {
                // Podríamos validar campos de la pestaña actual aquí
                goToTab(idx + 1);
            });
        });

        prevBtns.forEach((btn, idx) => {
            btn.addEventListener('click', () => {
                goToTab(idx);
            });
        });

        // Toggle Alergias
        const alergiasCheck = document.getElementById('alergiasCheck');
        const alergiasText = document.getElementById('alergiasTab2');
        alergiasCheck.addEventListener('change', function() {
            alergiasText.classList.toggle('d-none', !this.checked);
            if(this.checked) alergiasText.focus();
        });

        // Validación Bootstrap
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                        // Resaltar que hay error (opcional: ir a la pestaña del primer error)
                        alert('Por favor complete todos los campos obligatorios marcados con *');
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    });
</script>

<style>
    .nav-tabs .nav-link {
        color: #6c757d;
        border-bottom: 3px solid transparent;
        transition: all 0.3s;
    }
    .nav-tabs .nav-link.active {
        color: #0d6efd !important;
        border-bottom: 3px solid #0d6efd !important;
        background-color: #fff !important;
    }
    .nav-tabs .nav-link:hover {
        background-color: #f8f9fa;
        border-bottom: 3px solid #dee2e6;
    }
    .card-header {
        background: linear-gradient(45deg, #0d6efd, #0d47a1) !important;
    }
    .form-label {
        margin-bottom: 0.3rem;
    }
</style>

<?php 
$content = ob_get_clean(); 
require_once VIEWS_PATH . '/layouts/base.php'; 
?>
