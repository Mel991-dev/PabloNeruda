<?php

namespace App\Application\Controllers;
use App\Core\{Request, Response, Session};
use App\Domain\Services\EstudianteService;
use App\Infrastructure\Repositories\{MySQLEstudianteRepository, MySQLAcudienteRepository, MySQLCursoRepository, MySQLFamiliarRepository};

class EstudianteController
{
    private EstudianteService $service;

    public function __construct()
    {
        // Inyección de dependencias manual (por ahora)
        $this->service = new EstudianteService(
            new MySQLEstudianteRepository(),
            new MySQLAcudienteRepository(),
            new MySQLCursoRepository(),
            new MySQLFamiliarRepository()
        );
    }

    /**
     * Listar estudiantes
     */
    public function index(): void
    {
        $estudiantes = $this->service->listarEstudiantes();
        
        Response::view('estudiantes.index', [
            'estudiantes' => $estudiantes,
            'titulo' => 'Gestión de Estudiantes'
        ]);
    }

    /**
     * Mostrar formulario de creación
     */
    public function create(): void
    {
        $cursos = $this->service->listarCursos();
        
        Response::view('estudiantes.create', [
            'cursos' => $cursos,
            'titulo' => 'Registrar Estudiante'
        ]);
    }

    /**
     * Guardar nuevo estudiante
     */
    public function store(): void
    {
        $request = new Request();
        
        if (!$request->isPost()) {
            Response::redirect(APP_URL . '/estudiantes/crear');
            return;
        }

        try {
            $datosEstudiante = [
                'nombre' => $request->input('nombre'),
                'apellido' => $request->input('apellido'),
                'fecha_nacimiento' => $request->input('fecha_nacimiento'),
                'tipo_documento' => $request->input('tipo_documento'),
                'numero_documento' => $request->input('numero_documento'),
                'registro_civil' => $request->input('registro_civil'),
                'tarjeta_identidad' => $request->input('tarjeta_identidad'),
                'tiene_alergias' => $request->input('tiene_alergias') === '1',
                'descripcion_alergias' => $request->input('descripcion_alergias'),
                'numero_hermanos' => (int)$request->input('numero_hermanos'),
                'curso_id' => (int)$request->input('curso'),
                'lugar_nacimiento' => $request->input('lugar_nacimiento'),
                'procedencia_institucion' => $request->input('procedencia'),
                'estado' => 'Activo'
            ];

            // Info Salud
            $datosEstudiante['info_salud'] = [
                'eps' => $request->input('eps'),
                'tipo_sangre' => $request->input('tipo_sangre'),
                'limitaciones_fisicas' => $request->input('lim_fisicas'),
                'limitaciones_sensoriales' => $request->input('lim_sensoriales'),
                'medicamentos_permanentes' => $request->input('meds_permanentes'),
                'vacunas_completas' => $request->input('vacunas') === '1',
                'toma_medicamentos' => $request->input('toma_meds') === '1',
                'alergico_a' => $request->input('alergico_a'),
                'dificultad_salud' => $request->input('dificultad_salud')
            ];

            // Info Socioeconómica
            $datosEstudiante['info_socio'] = [
                'sisben_nivel' => $request->input('sisben'),
                'estrato' => (int)$request->input('estrato'),
                'barrio' => $request->input('barrio'),
                'sector' => $request->input('sector'),
                'tipo_vivienda' => $request->input('vivienda'),
                'tiene_internet' => $request->input('internet') === '1',
                'servicios_publicos_completo' => $request->input('servicios') === '1',
                'victima_conflicto' => $request->input('victima') === '1',
                'victima_conflicto_detalle' => $request->input('victima_detalle'),
                'grupo_etnico' => $request->input('etnia'),
                'resguardo_indigena' => $request->input('resguardo'),
                'familias_en_accion' => $request->input('familias_en_accion') === '1',
                'poblacion_desplazada' => $request->input('desplazado') === '1',
                'lugar_desplazamiento' => $request->input('lugar_desplazamiento')
            ];

            // Padres
            if ($request->input('padre_nombre')) {
                $datosEstudiante['padre'] = [
                    'nombre' => $request->input('padre_nombre'),
                    'apellido' => $request->input('padre_apellido'),
                    'tipo_documento' => $request->input('padre_tipo_doc'),
                    'numero_documento' => $request->input('padre_num_doc'),
                    'ocupacion' => $request->input('padre_ocupacion'),
                    'empresa' => $request->input('padre_empresa'),
                    'telefono' => $request->input('padre_tel'),
                    'email' => $request->input('padre_email'),
                    'direccion' => $request->input('padre_direccion'),
                    'nivel_educativo' => $request->input('padre_nivel_educativo'),
                    'vive_con_estudiante' => $request->input('padre_vive') === '1'
                ];
            }

            if ($request->input('madre_nombre')) {
                $datosEstudiante['madre'] = [
                    'nombre' => $request->input('madre_nombre'),
                    'apellido' => $request->input('madre_apellido'),
                    'tipo_documento' => $request->input('madre_tipo_doc'),
                    'numero_documento' => $request->input('madre_num_doc'),
                    'ocupacion' => $request->input('madre_ocupacion'),
                    'empresa' => $request->input('madre_empresa'),
                    'telefono' => $request->input('madre_tel'),
                    'email' => $request->input('madre_email'),
                    'direccion' => $request->input('madre_direccion'),
                    'nivel_educativo' => $request->input('madre_nivel_educativo'),
                    'vive_con_estudiante' => $request->input('madre_vive') === '1'
                ];
            }

            // Antecedentes (Simplificado: capturamos el último colegio si viene)
            if ($request->input('ant_institucion')) {
                $datosEstudiante['antecedentes'] = [[
                    'nivel_educativo' => $request->input('ant_nivel'),
                    'institucion' => $request->input('ant_institucion'),
                    'años_cursados' => $request->input('ant_anios'),
                    'motivo_retiro' => $request->input('ant_motivo'),
                    'observaciones' => $request->input('ant_observaciones')
                ]];
            }

            $datosAcudiente = [
                'nombre' => $request->input('acudiente_nombre'),
                'apellido' => $request->input('acudiente_apellido'),
                'tipo_documento' => $request->input('acudiente_tipo_documento'),
                'numero_documento' => $request->input('acudiente_numero_documento'),
                'telefono' => $request->input('acudiente_telefono'),
                'telefono_secundario' => $request->input('acudiente_telefono_secundario'),
                'email' => $request->input('acudiente_email'),
                'direccion' => $request->input('acudiente_direccion'),
                'ocupacion' => $request->input('acudiente_ocupacion'),
                'parentesco' => $request->input('acudiente_parentesco'),
                'con_quien_vive' => $request->input('con_quien_vive')
            ];

            $archivoPdf = $_FILES['documento_pdf'] ?? null;

            $this->service->registrarEstudiante($datosEstudiante, $datosAcudiente, $archivoPdf);

            Session::flash('success', 'Estudiante registrado correctamente.');
            Response::redirect(APP_URL . '/estudiantes');

        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            // TODO: En el futuro implementar old input para no perder datos
            Response::redirect(APP_URL . '/estudiantes/crear');
        }
    }

    /**
     * Ver detalles del estudiante
     */
    public function show(int $id = 0): void
    {
        $request = new Request();
        $id = $id ?: (int)$request->query('id');
        
        if (!$id) {
            Session::flash('error', 'ID de estudiante no especificado.');
            Response::redirect(APP_URL . '/estudiantes');
            return;
        }

        $data = $this->service->obtenerEstudiante($id);
        
        if (empty($data)) {
            Response::error(404, "Estudiante no encontrado");
            return;
        }

        Response::view('estudiantes.view', [
            'estudiante' => $data['estudiante'],
            'acudientes' => $data['acudientes'],
            'titulo' => 'Detalle del Estudiante'
        ]);
    }
    /**
     * Mostrar formulario de edición
     */
    public function edit(): void
    {
        $request = new Request();
        $id = (int)$request->query('id');

        if (!$id) {
            Session::flash('error', 'ID de estudiante no especificado.');
            Response::redirect(APP_URL . '/estudiantes');
            return;
        }

        $data = $this->service->obtenerEstudiante($id);
        
        if (empty($data)) {
            Response::error(404, "Estudiante no encontrado");
            return;
        }

        // Obtener el acudiente principal (asumimos el primero por ahora)
        $acudientePrincipal = $data['acudientes'][0] ?? null;

        Response::view('estudiantes.edit', [
            'estudiante' => $data['estudiante'],
            'acudiente' => $acudientePrincipal,
            'titulo' => 'Editar Estudiante'
        ]);
    }

    /**
     * Actualizar estudiante
     */
    public function update(): void
    {
        $request = new Request();
        
        if (!$request->isPost()) {
            Response::redirect(APP_URL . '/estudiantes');
            return;
        }

        $id = (int)$request->input('id_estudiante');
        if (!$id) {
            Session::flash('error', 'ID de estudiante no válido.');
            Response::redirect(APP_URL . '/estudiantes');
            return;
        }

        try {
            $datosEstudiante = [
                'nombre' => $request->input('nombre'),
                'apellido' => $request->input('apellido'),
                'fecha_nacimiento' => $request->input('fecha_nacimiento'),
                'tipo_documento' => $request->input('tipo_documento'),
                'numero_documento' => $request->input('numero_documento'),
                'registro_civil' => $request->input('registro_civil'),
                'tarjeta_identidad' => $request->input('tarjeta_identidad'),
                'tiene_alergias' => $request->input('tiene_alergias') === '1',
                'descripcion_alergias' => $request->input('descripcion_alergias'),
                'numero_hermanos' => (int)$request->input('numero_hermanos'),
                'lugar_nacimiento' => $request->input('lugar_nacimiento'),
                'procedencia_institucion' => $request->input('procedencia'),
                // El curso no se edita aquí por ahora para no complicar matrículas
            ];

             // Info Salud
            $datosEstudiante['info_salud'] = [
                'eps' => $request->input('eps'),
                'tipo_sangre' => $request->input('tipo_sangre'),
                'limitaciones_fisicas' => $request->input('lim_fisicas'),
                'limitaciones_sensoriales' => $request->input('lim_sensoriales'),
                'medicamentos_permanentes' => $request->input('meds_permanentes'),
                'vacunas_completas' => $request->input('vacunas') === '1',
                'toma_medicamentos' => $request->input('toma_meds') === '1',
                'alergico_a' => $request->input('alergico_a'),
                'dificultad_salud' => $request->input('dificultad_salud')
            ];

            // Info Socioeconómica
            $datosEstudiante['info_socio'] = [
                'sisben_nivel' => $request->input('sisben'),
                'estrato' => (int)$request->input('estrato'),
                'barrio' => $request->input('barrio'),
                'sector' => $request->input('sector'),
                'tipo_vivienda' => $request->input('vivienda'),
                'tiene_internet' => $request->input('internet') === '1',
                'servicios_publicos_completo' => $request->input('servicios') === '1',
                'victima_conflicto' => $request->input('victima') === '1',
                'victima_conflicto_detalle' => $request->input('victima_detalle'),
                'grupo_etnico' => $request->input('etnia'),
                'resguardo_indigena' => $request->input('resguardo'),
                'familias_en_accion' => $request->input('familias_en_accion') === '1',
                'poblacion_desplazada' => $request->input('desplazado') === '1',
                'lugar_desplazamiento' => $request->input('lugar_desplazamiento')
            ];

            // Padres
            if ($request->input('padre_nombre')) {
                $datosEstudiante['padre'] = [
                    'nombre' => $request->input('padre_nombre'),
                    'apellido' => $request->input('padre_apellido'),
                    'tipo_documento' => $request->input('padre_tipo_doc'),
                    'numero_documento' => $request->input('padre_num_doc'),
                    'ocupacion' => $request->input('padre_ocupacion'),
                    'empresa' => $request->input('padre_empresa'),
                    'telefono' => $request->input('padre_tel'),
                    'email' => $request->input('padre_email'),
                    'direccion' => $request->input('padre_direccion'),
                    'nivel_educativo' => $request->input('padre_nivel_educativo'),
                    'vive_con_estudiante' => $request->input('padre_vive') === '1'
                ];
            }

            if ($request->input('madre_nombre')) {
                $datosEstudiante['madre'] = [
                    'nombre' => $request->input('madre_nombre'),
                    'apellido' => $request->input('madre_apellido'),
                    'tipo_documento' => $request->input('madre_tipo_doc'),
                    'numero_documento' => $request->input('madre_num_doc'),
                    'ocupacion' => $request->input('madre_ocupacion'),
                    'empresa' => $request->input('madre_empresa'),
                    'telefono' => $request->input('madre_tel'),
                    'email' => $request->input('madre_email'),
                    'direccion' => $request->input('madre_direccion'),
                    'nivel_educativo' => $request->input('madre_nivel_educativo'),
                    'vive_con_estudiante' => $request->input('madre_vive') === '1'
                ];
            }

            // Antecedentes
            if ($request->input('ant_institucion')) {
                $datosEstudiante['antecedentes'] = [[
                    'nivel_educativo' => $request->input('ant_nivel'),
                    'institucion' => $request->input('ant_institucion'),
                    'años_cursados' => $request->input('ant_anios'),
                    'motivo_retiro' => $request->input('ant_motivo'),
                    'observaciones' => $request->input('ant_observaciones')
                ]];
            }

            $datosAcudiente = [
                'nombre' => $request->input('acudiente_nombre'),
                'apellido' => $request->input('acudiente_apellido'),
                'tipo_documento' => $request->input('acudiente_tipo_documento'),
                'numero_documento' => $request->input('acudiente_numero_documento'),
                'telefono' => $request->input('acudiente_telefono'),
                'telefono_secundario' => $request->input('acudiente_telefono_secundario'),
                'email' => $request->input('acudiente_email'),
                'direccion' => $request->input('acudiente_direccion'),
                'ocupacion' => $request->input('acudiente_ocupacion'),
                'parentesco' => $request->input('acudiente_parentesco'),
                'con_quien_vive' => $request->input('con_quien_vive')
            ];

            $archivoPdf = $_FILES['documento_pdf'] ?? null;

            $this->service->actualizarEstudiante($id, $datosEstudiante, $datosAcudiente, $archivoPdf);

            Session::flash('success', 'Estudiante actualizado correctamente.');
            Response::redirect(APP_URL . '/estudiantes/ver?id=' . $id);

        } catch (\Exception $e) {
            Session::flash('error', 'Error al actualizar: ' . $e->getMessage());
            Response::redirect(APP_URL . '/estudiantes/editar?id=' . $id);
        }
    }
}
