<?php
$pageTitle = 'Editar Estudiante';
ob_start();

$salud = $estudiante->getInfoSalud();
$socio = $estudiante->getInfoSocioeconomica();
$padre = $estudiante->getPadre();
$madre = $estudiante->getMadre();
$antecedentes = $estudiante->getAntecedentesAcademicos();
$primerAntecedente = $antecedentes[0] ?? null;
?>

<div class="row justify-content-center">
    <div class="col-lg-11">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-warning text-dark py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Editar Ficha Estudiantil V2.0</h5>
                <span class="badge bg-dark text-white">ID: <?php echo $estudiante->getIdEstudiante(); ?></span>
            </div>
            <div class="card-body p-0">
                <form action="<?php echo APP_URL; ?>/estudiantes/actualizar" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate id="formEditEstudiante">
                    <input type="hidden" name="id_estudiante" value="<?php echo $estudiante->getIdEstudiante(); ?>">

                    <!-- Navegación de Pestañas -->
                    <ul class="nav nav-tabs nav-justified bg-light" id="editTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active py-3 fw-bold" id="edit-basicos-tab" data-bs-toggle="tab" data-bs-target="#edit-basicos" type="button" role="tab">
                                <i class="bi bi-1-circle-fill me-1"></i> Datos Básicos
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 fw-bold" id="edit-salud-tab" data-bs-toggle="tab" data-bs-target="#edit-salud" type="button" role="tab">
                                <i class="bi bi-2-circle-fill me-1"></i> Salud y Socio
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 fw-bold" id="edit-familia-tab" data-bs-toggle="tab" data-bs-target="#edit-familia" type="button" role="tab">
                                <i class="bi bi-3-circle-fill me-1"></i> Núcleo Familiar
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 fw-bold" id="edit-acudiente-tab" data-bs-toggle="tab" data-bs-target="#edit-acudiente" type="button" role="tab">
                                <i class="bi bi-4-circle-fill me-1"></i> Acudiente y Acad.
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content p-4" id="editTabsContent">
                        
                        <!-- TAB 1: DATOS BÁSICOS -->
                        <div class="tab-pane fade show active" id="edit-basicos" role="tabpanel">
                            <h6 class="text-primary border-bottom pb-2 mb-3">Información de Identidad</h6>
                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Nombres <span class="text-danger">*</span></label>
                                    <input type="text" name="nombre" class="form-control" value="<?php echo htmlspecialchars($estudiante->getNombre()); ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Apellidos <span class="text-danger">*</span></label>
                                    <input type="text" name="apellido" class="form-control" value="<?php echo htmlspecialchars($estudiante->getApellido()); ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold">Fecha de Nacimiento <span class="text-danger">*</span></label>
                                    <input type="date" name="fecha_nacimiento" class="form-control" value="<?php echo $estudiante->getFechaNacimiento(); ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Tipo Documento <span class="text-danger">*</span></label>
                                    <select name="tipo_documento" class="form-select" required>
                                        <option value="RC" <?php echo $estudiante->getTipoDocumento() === 'RC' ? 'selected' : ''; ?>>Registro Civil</option>
                                        <option value="TI" <?php echo $estudiante->getTipoDocumento() === 'TI' ? 'selected' : ''; ?>>Tarjeta de Identidad</option>
                                        <option value="CC" <?php echo $estudiante->getTipoDocumento() === 'CC' ? 'selected' : ''; ?>>Cédula de Ciudadanía</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Número Documento <span class="text-danger">*</span></label>
                                    <input type="text" name="numero_documento" class="form-control" value="<?php echo htmlspecialchars($estudiante->getNumeroDocumento()); ?>" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Lugar de Nacimiento</label>
                                    <input type="text" name="lugar_nacimiento" class="form-control" value="<?php echo htmlspecialchars($estudiante->getLugarNacimiento() ?? ''); ?>">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Número de Hermanos</label>
                                    <input type="number" name="numero_hermanos" class="form-control" min="0" value="<?php echo $estudiante->getNumeroHermanos(); ?>">
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Registro Civil (NUIP)</label>
                                    <input type="text" name="registro_civil" class="form-control" value="<?php echo htmlspecialchars($estudiante->getRegistroCivil() ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Tarjeta de Identidad</label>
                                    <input type="text" name="tarjeta_identidad" class="form-control" value="<?php echo htmlspecialchars($estudiante->getTarjetaIdentidad() ?? ''); ?>">
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label small fw-bold">Documento PDF (Actualizar)</label>
                                    <input type="file" name="documento_pdf" class="form-control" accept=".pdf">
                                    <?php if ($estudiante->getDocumentoPdf()): ?>
                                        <div class="mt-2 p-2 bg-light border rounded small">
                                            <i class="bi bi-file-earmark-pdf text-danger"></i> Archivo actual: 
                                            <a href="<?php echo APP_URL . '/' . $estudiante->getDocumentoPdf(); ?>" target="_blank">Ver documento</a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="text-end">
                                <button type="button" class="btn btn-primary px-4 btn-next-tab">Siguiente <i class="bi bi-arrow-right"></i></button>
                            </div>
                        </div>

                        <!-- TAB 2: SALUD Y SOCIOECONÓMICO -->
                        <div class="tab-pane fade" id="edit-salud" role="tabpanel">
                             <div class="row">
                                <div class="col-md-6 border-end">
                                    <h6 class="text-danger border-bottom pb-2 mb-3"><i class="bi bi-heart-pulse"></i> Salud</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">EPS</label>
                                            <input type="text" name="eps" class="form-control" value="<?php echo $salud ? htmlspecialchars($salud->getEps() ?? '') : ''; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Tipo Sangre</label>
                                            <select name="tipo_sangre" class="form-select">
                                                <option value="">Seleccione...</option>
                                                <?php foreach(['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'] as $t): ?>
                                                    <option value="<?php echo $t; ?>" <?php echo ($salud && $salud->getTipoSangre() === $t) ? 'selected' : ''; ?>><?php echo $t; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-bold">Limitaciones</label>
                                            <input type="text" name="lim_fisicas" class="form-control" value="<?php echo $salud ? htmlspecialchars($salud->getLimitacionesFisicas() ?? '') : ''; ?>">
                                        </div>
                                        <div class="col-12">
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox" name="tiene_alergias" value="1" id="alergiasCheckEdit" <?php echo $estudiante->tieneAlergias() ? 'checked' : ''; ?>>
                                                <label class="form-check-label small fw-bold" for="alergiasCheckEdit">¿Tiene Alergias?</label>
                                            </div>
                                            <textarea name="descripcion_alergias" id="alergiasTextEdit" class="form-control <?php echo $estudiante->tieneAlergias() ? '' : 'd-none'; ?>" rows="2"><?php echo htmlspecialchars($estudiante->getDescripcionAlergias() ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-success border-bottom pb-2 mb-3"><i class="bi bi-house"></i> Socioeconómico</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Sisben</label>
                                            <input type="text" name="sisben" class="form-control" value="<?php echo $socio ? htmlspecialchars($socio->getSisbenNivel() ?? '') : ''; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Estrato</label>
                                            <input type="number" name="estrato" class="form-control" value="<?php echo $socio ? $socio->getEstrato() : '1'; ?>">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-bold">Barrio de Residencia</label>
                                            <input type="text" name="barrio" class="form-control" value="<?php echo $socio ? htmlspecialchars($socio->getBarrio() ?? '') : ''; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="internet" value="1" <?php echo ($socio && $socio->tieneInternet()) ? 'checked' : ''; ?>>
                                                <label class="form-check-label small">Tiene Internet</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="victima" value="1" <?php echo ($socio && $socio->esVictimaConflicto()) ? 'checked' : ''; ?>>
                                                <label class="form-check-label small text-danger fw-bold">Víctima conflicto</label>
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

                        <!-- TAB 3: NÚCLEO FAMILIAR -->
                        <div class="tab-pane fade" id="edit-familia" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 border-end">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">Padre</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Nombres</label>
                                            <input type="text" name="padre_nombre" class="form-control" value="<?php echo $padre ? htmlspecialchars($padre->getNombre()) : ''; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Apellidos</label>
                                            <input type="text" name="padre_apellido" class="form-control" value="<?php echo $padre ? htmlspecialchars($padre->getApellido()) : ''; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Cédula</label>
                                            <input type="text" name="padre_num_doc" class="form-control" value="<?php echo $padre ? htmlspecialchars($padre->getNumeroDocumento()) : ''; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Teléfono</label>
                                            <input type="text" name="padre_tel" class="form-control" value="<?php echo $padre ? htmlspecialchars($padre->getTelefono() ?? '') : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">Madre</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Nombres</label>
                                            <input type="text" name="madre_nombre" class="form-control" value="<?php echo $madre ? htmlspecialchars($madre->getNombre()) : ''; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Apellidos</label>
                                            <input type="text" name="madre_apellido" class="form-control" value="<?php echo $madre ? htmlspecialchars($madre->getApellido()) : ''; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Cédula</label>
                                            <input type="text" name="madre_num_doc" class="form-control" value="<?php echo $madre ? htmlspecialchars($madre->getNumeroDocumento()) : ''; ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Teléfono</label>
                                            <input type="text" name="madre_tel" class="form-control" value="<?php echo $madre ? htmlspecialchars($madre->getTelefono() ?? '') : ''; ?>">
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
                        <div class="tab-pane fade" id="edit-acudiente" role="tabpanel">
                            <div class="row">
                                <div class="col-md-7 border-end">
                                    <h6 class="text-secondary border-bottom pb-2 mb-3">Acudiente Principal</h6>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Nombre <span class="text-danger">*</span></label>
                                            <input type="text" name="acudiente_nombre" class="form-control" value="<?php echo htmlspecialchars($acudiente['nombre'] ?? ''); ?>" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label small fw-bold">Parentesco</label>
                                            <select name="acudiente_parentesco" class="form-select">
                                                <?php foreach(['Padre', 'Madre', 'Abuelo/a', 'Tío/a', 'Otro'] as $p): ?>
                                                    <option value="<?php echo $p; ?>" <?php echo (($acudiente['parentesco'] ?? '') === $p) ? 'selected' : ''; ?>><?php echo $p; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label small fw-bold">Teléfono Emergencia <span class="text-danger">*</span></label>
                                            <input type="text" name="acudiente_telefono" class="form-control" value="<?php echo htmlspecialchars($acudiente['telefono'] ?? ''); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <h6 class="text-info border-bottom pb-2 mb-3">Procedencia</h6>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label class="form-label small fw-bold">Colegio Procedencia</label>
                                            <input type="text" name="procedencia" class="form-control" value="<?php echo htmlspecialchars($estudiante->getProcedenciaInstitucion() ?? ''); ?>">
                                        </div>
                                        <div class="col-12">
                                            <label class="form-label small fw-bold">Último colegio (Académico)</label>
                                            <input type="text" name="ant_institucion" class="form-control" value="<?php echo $primerAntecedente ? htmlspecialchars($primerAntecedente->getInstitucion()) : ''; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary btn-prev-tab"><i class="bi bi-arrow-left"></i> Anterior</button>
                                <button type="submit" class="btn btn-success px-5 fw-bold"><i class="bi bi-save"></i> GUARDAR CAMBIOS</button>
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
        const tabs = document.querySelectorAll('#editTabs button');
        const nextBtns = document.querySelectorAll('.btn-next-tab');
        const prevBtns = document.querySelectorAll('.btn-prev-tab');

        function goToTab(index) {
            const nextTab = new bootstrap.Tab(tabs[index]);
            nextTab.show();
            window.scrollTo(0, 0);
        }

        nextBtns.forEach((btn, idx) => { btn.addEventListener('click', () => { goToTab(idx + 1); }); });
        prevBtns.forEach((btn, idx) => { btn.addEventListener('click', () => { goToTab(idx); }); });

        const alergiasCheckEdit = document.getElementById('alergiasCheckEdit');
        const alergiasTextEdit = document.getElementById('alergiasTextEdit');
        alergiasCheckEdit.addEventListener('change', function() {
            alergiasTextEdit.classList.toggle('d-none', !this.checked);
        });

        // Validación simple
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    alert('Complete los campos obligatorios (*)');
                }
                form.classList.add('was-validated');
            }, false);
        });
    });
</script>

<style>
    .nav-tabs .nav-link { color: #6c757d; border-bottom: 3px solid transparent; }
    .nav-tabs .nav-link.active { color: #d63384 !important; border-bottom: 3px solid #d63384 !important; font-weight: bold; background: #fff !important; }
</style>

<?php 
$content = ob_get_clean(); 
require_once VIEWS_PATH . '/layouts/base.php'; 
?>
