<?php
$pageTitle = 'Ficha Integral del Estudiante';
ob_start();

$salud = $estudiante->getInfoSalud();
$socio = $estudiante->getInfoSocioeconomica();
$antecedentes = $estudiante->getAntecedentesAcademicos();
?>

<div class="row mb-4 align-items-center">
    <div class="col-md-7">
        <h2 class="mb-1"><i class="bi bi-person-badge text-primary"></i> <?php echo htmlspecialchars($estudiante->getNombreCompleto()); ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/dashboard">Inicio</a></li>
                <li class="breadcrumb-item"><a href="<?php echo APP_URL; ?>/estudiantes">Estudiantes</a></li>
                <li class="breadcrumb-item active">Ficha Integral</li>
            </ol>
        </nav>
    </div>
    <div class="col-md-5 text-end">
        <div class="btn-group">
            <a href="<?php echo APP_URL; ?>/estudiantes/editar?id=<?php echo $estudiante->getIdEstudiante(); ?>" class="btn btn-warning">
                <i class="bi bi-pencil-square"></i> Editar Ficha
            </a>
            <button onclick="window.print()" class="btn btn-outline-secondary">
                <i class="bi bi-printer"></i> Imprimir
            </button>
            <a href="<?php echo APP_URL; ?>/estudiantes" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- COLUMNA IZQUIERDA: BASAL Y SALUD -->
    <div class="col-lg-4">
        <!-- Card Foto y Estado -->
        <div class="card shadow-sm mb-4 border-0">
            <div class="card-body text-center p-4">
                <div class="mb-3">
                    <i class="bi bi-person-circle display-1 text-secondary"></i>
                </div>
                <h4 class="mb-1"><?php echo htmlspecialchars($estudiante->getNombre()); ?></h4>
                <p class="text-muted small mb-3"><?php echo htmlspecialchars($estudiante->getTipoDocumento() . ': ' . $estudiante->getNumeroDocumento()); ?></p>
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge bg-<?php echo $estudiante->getEstado() === 'Activo' ? 'success' : 'danger'; ?> px-3 py-2">
                        <?php echo $estudiante->getEstado(); ?>
                    </span>
                    <span class="badge bg-primary px-3 py-2">
                        Estudiante Nerudista
                    </span>
                </div>
                <hr>
                <div class="text-start">
                    <p class="small mb-1 text-secondary fw-bold">Fecha Registro:</p>
                    <p class="small"><?php echo $estudiante->getFechaRegistro() ?: date('Y-m-d'); ?></p>
                </div>
            </div>
        </div>

        <!-- Card Salud -->
        <div class="card shadow-sm mb-4 border-0 border-start border-danger border-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 text-danger fw-bold"><i class="bi bi-heart-pulse-fill"></i> Información Médica</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody class="small">
                        <tr>
                            <th class="ps-3 py-2 bg-light w-40 text-secondary">EPS:</th>
                            <td class="ps-2 py-2 fw-bold text-dark"><?php echo $salud ? htmlspecialchars($salud->getEps()) : '-'; ?></td>
                        </tr>
                        <tr>
                            <th class="ps-3 py-2 bg-light text-secondary">Tipo Sangre:</th>
                            <td class="ps-2 py-2 fw-bold text-danger"><?php echo $salud ? $salud->getTipoSangre() : '-'; ?></td>
                        </tr>
                        <tr>
                            <th class="ps-3 py-2 bg-light text-secondary">Alergias:</th>
                            <td class="ps-2 py-2">
                                <?php if ($estudiante->tieneAlergias()): ?>
                                    <span class="text-danger fw-bold"><i class="bi bi-exclamation-triangle"></i> SÍ</span>
                                    <div class="mt-1 small border p-1 rounded bg-light-warning">
                                        <?php echo htmlspecialchars($estudiante->getDescripcionAlergias()); ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-success">No reporta</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="ps-3 py-2 bg-light text-secondary">Limitaciones:</th>
                            <td class="ps-2 py-2"><?php echo $salud ? htmlspecialchars($salud->getLimitacionesFisicas() ?? 'Ninguna') : 'Ninguna'; ?></td>
                        </tr>
                        <tr>
                            <th class="ps-3 py-2 bg-light text-secondary">Vacunas:</th>
                            <td class="ps-2 py-2">
                                <?php if ($salud && $salud->areVacunasCompletas()): ?>
                                    <i class="bi bi-check-circle-fill text-success"></i> Completas
                                <?php else: ?>
                                    <i class="bi bi-dash-circle text-warning"></i> Incompletas/No registra
                                <?php endif; ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA: ACADÉMICO, SOCIO Y FAMILIA -->
    <div class="col-lg-8">
        <!-- Tabs de Información Detallada -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-light p-0">
                <ul class="nav nav-tabs border-0" id="detailTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active py-3 px-4 border-0 border-bottom border-3" id="bio-tab" data-bs-toggle="tab" data-bs-target="#bio">
                             <i class="bi bi-info-circle"></i> Biográficos
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-3 px-4 border-0 border-bottom border-3" id="socio-tab" data-bs-toggle="tab" data-bs-target="#sociotab">
                             <i class="bi bi-house"></i> Entorno Social
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-3 px-4 border-0 border-bottom border-3" id="parents-tab" data-bs-toggle="tab" data-bs-target="#parentstab">
                             <i class="bi bi-people"></i> Núcleo Familiar
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link py-3 px-4 border-0 border-bottom border-3" id="acu-tab" data-bs-toggle="tab" data-bs-target="#acutab">
                             <i class="bi bi-person-check"></i> Acudiente
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <div class="tab-content">
                    
                    <!-- TAB: BIOGRÁFICOS -->
                    <div class="tab-pane fade show active" id="bio">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label class="text-secondary small fw-bold d-block">Edad:</label>
                                <span class="fs-5 fw-bold"><?php echo $estudiante->calcularEdad(); ?> Años</span>
                            </div>
                            <div class="col-md-6">
                                <label class="text-secondary small fw-bold d-block">Lugar de Nacimiento:</label>
                                <span class="fs-5"><?php echo htmlspecialchars($estudiante->getLugarNacimiento() ?: 'No registra'); ?></span>
                            </div>
                            <div class="col-md-6">
                                <label class="text-secondary small fw-bold d-block">Procedencia Académica:</label>
                                <span class="fs-5"><?php echo htmlspecialchars($estudiante->getProcedenciaInstitucion() ?: 'Nuevo ingreso'); ?></span>
                            </div>
                            <div class="col-md-6">
                                <label class="text-secondary small fw-bold d-block">Hermanos en la institución:</label>
                                <span class="fs-5"><?php echo $estudiante->getNumeroHermanos(); ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: SOCIOECONÓMICO -->
                    <div class="tab-pane fade" id="sociotab">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="p-3 border rounded bg-light">
                                    <small class="d-block text-secondary fw-bold border-bottom mb-2 pb-1">Básico</small>
                                    <p class="mb-1"><strong>Estrato:</strong> <?php echo $socio ? $socio->getEstrato() : '-'; ?></p>
                                    <p class="mb-1"><strong>Sisben:</strong> <?php echo $socio ? htmlspecialchars($socio->getSisbenNivel()) : '-'; ?></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 border rounded bg-light">
                                    <small class="d-block text-secondary fw-bold border-bottom mb-2 pb-1">Vivienda</small>
                                    <p class="mb-1"><strong>Tipo:</strong> <?php echo $socio ? htmlspecialchars($socio->getTipoVivienda() ?? '-') : '-'; ?></p>
                                    <p class="mb-1"><strong>Barrio:</strong> <?php echo $socio ? htmlspecialchars($socio->getBarrio() ?? '-') : '-'; ?></p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 border rounded bg-light">
                                    <small class="d-block text-secondary fw-bold border-bottom mb-2 pb-1">Condición Especial</small>
                                    <p class="mb-1"><strong>Víctima:</strong> <?php echo ($socio && $socio->esVictimaConflicto()) ? '<span class="text-danger">SÍ</span>' : 'No'; ?></p>
                                    <p class="mb-1"><strong>Etnia:</strong> <?php echo $socio ? htmlspecialchars($socio->getGrupoEtnico() ?? 'Ninguna') : 'Ninguna'; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: PADRES -->
                    <div class="tab-pane fade" id="parentstab">
                        <div class="row g-3">
                            <div class="col-md-6 border-end">
                                <h6 class="fw-bold text-primary mb-3">Información del Padre</h6>
                                <?php if ($estudiante->getPadre()): ?>
                                    <p class="mb-1"><strong>Nombre:</strong> <?php echo htmlspecialchars($estudiante->getPadre()->getNombreCompleto()); ?></p>
                                    <p class="mb-1"><strong>Doc:</strong> <?php echo $estudiante->getPadre()->getNumeroDocumento(); ?></p>
                                    <p class="mb-1"><strong>Tel:</strong> <?php echo htmlspecialchars($estudiante->getPadre()->getTelefono() ?? '-'); ?></p>
                                    <p class="mb-1"><strong>Ocupación:</strong> <?php echo htmlspecialchars($estudiante->getPadre()->getOcupacion() ?? '-'); ?></p>
                                <?php else: ?>
                                    <p class="text-muted small italic">No hay información del padre registrada.</p>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6 ps-md-4">
                                <h6 class="fw-bold text-primary mb-3">Información de la Madre</h6>
                                <?php if ($estudiante->getMadre()): ?>
                                    <p class="mb-1"><strong>Nombre:</strong> <?php echo htmlspecialchars($estudiante->getMadre()->getNombreCompleto()); ?></p>
                                    <p class="mb-1"><strong>Doc:</strong> <?php echo $estudiante->getMadre()->getNumeroDocumento(); ?></p>
                                    <p class="mb-1"><strong>Tel:</strong> <?php echo htmlspecialchars($estudiante->getMadre()->getTelefono() ?? '-'); ?></p>
                                    <p class="mb-1"><strong>Ocupación:</strong> <?php echo htmlspecialchars($estudiante->getMadre()->getOcupacion() ?? '-'); ?></p>
                                <?php else: ?>
                                    <p class="text-muted small italic">No hay información de la madre registrada.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- TAB: ACUDIENTE -->
                    <div class="tab-pane fade" id="acutab">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nombre Completo</th>
                                        <th>Parentesco</th>
                                        <th>Teléfono</th>
                                        <th>Dirección</th>
                                        <th>Principal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($acudientes as $acu): ?>
                                    <tr>
                                        <td class="fw-bold"><?php echo htmlspecialchars($acu['nombre'] . ' ' . $acu['apellido']); ?></td>
                                        <td><?php echo htmlspecialchars($acu['parentesco']); ?></td>
                                        <td><?php echo htmlspecialchars($acu['telefono']); ?></td>
                                        <td><?php echo htmlspecialchars($acu['direccion'] ?? '-'); ?></td>
                                        <td>
                                            <?php if ($acu['es_acudiente_principal']): ?>
                                                <span class="badge bg-success">Sí</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">No</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Card Antecedentes -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 text-info fw-bold"><i class="bi bi-clock-history"></i> Historial de Instituciones Anteriores</h6>
            </div>
            <div class="card-body">
                <?php if (empty($antecedentes)): ?>
                    <p class="text-muted small mb-0">No registra antecedentes en otras instituciones.</p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($antecedentes as $ant): ?>
                            <div class="list-group-item px-0">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 fw-bold"><?php echo htmlspecialchars($ant->getInstitucion()); ?></h6>
                                    <small class="text-muted"><?php echo $ant->getAniosCursados(); ?> año(s)</small>
                                </div>
                                <p class="mb-1 small"><strong>Nivel:</strong> <?php echo $ant->getNivelEducativo(); ?> | <strong>Motivo:</strong> <?php echo htmlspecialchars($ant->getMotivoRetiro() ?? 'N/A'); ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn-group, .breadcrumb, ul.nav-tabs { display: none !important; }
        .tab-content > .tab-pane { display: block !important; opacity: 1 !important; }
        .card { box-shadow: none !important; border: 1px solid #ddd !important; }
        body { padding: 0; background: white; }
    }
    .nav-tabs .nav-link.active {
        color: #0d6efd !important;
        border-bottom-color: #0d6efd !important;
        background-color: transparent !important;
        font-weight: bold;
    }
    .w-40 { width: 40%; }
    .bg-light-warning { background-color: #fff9e6; }
</style>

<?php 
$content = ob_get_clean(); 
require_once VIEWS_PATH . '/layouts/base.php'; 
?>
