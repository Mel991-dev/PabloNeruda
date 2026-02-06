<?php

namespace App\Application\Controllers;

use App\Core\{Request, Response, Session};
use App\Infrastructure\Repositories\MySQLSeguimientoRepository;
use App\Infrastructure\Repositories\MySQLEstudianteRepository;
use App\Domain\Entities\Seguimiento;

/**
 * OrientacionController - Gestiona el bienestar estudiantil y alertas
 */
class OrientacionController
{
    private MySQLSeguimientoRepository $seguimientoRepo;
    private MySQLEstudianteRepository $estudianteRepo;

    public function __construct()
    {
        $this->seguimientoRepo = new MySQLSeguimientoRepository();
        $this->estudianteRepo = new MySQLEstudianteRepository();
    }

    /**
     * Dashboard del Orientador
     */
    public function index(): void
    {
        $alertas = $this->seguimientoRepo->getAlertasAcademicas();
        $alertasVulnerabilidad = $this->seguimientoRepo->getAlertasVulnerabilidad();
        
        Response::view('orientacion.dashboard', [
            'alertas' => $alertas,
            'alertas_vulnerabilidad' => $alertasVulnerabilidad,
            'titulo' => 'Módulo de Orientación'
        ]);
    }

    /**
     * Listado de seguimientos
     */
    public function listado(): void
    {
        $data = [
            'seguimientos' => $this->seguimientoRepo->findAll()
        ];
        Response::view('orientacion.index', $data);
    }

    /**
     * Formulario para crear un seguimiento
     */
    public function crear(): void
    {
        $request = new Request();
        $idEstudiante = (int) $request->query('id_estudiante');
        $estudiante = $this->estudianteRepo->findById($idEstudiante);
        
        if (!$estudiante) {
            Session::flash('error', 'Estudiante no encontrado para iniciar seguimiento.');
            Response::redirect(APP_URL . '/estudiantes');
            return;
        }

        Response::view('orientacion.crear', ['estudiante' => $estudiante]);
    }

    /**
     * Guardar un seguimiento
     */
    public function guardar(): void
    {
        $request = new Request();
        try {
            $datos = [
                'fk_estudiante' => $request->input('id_estudiante'),
                'fk_usuario_orientador' => Session::get('user_id'),
                'tipo_intervencion' => $request->input('tipo'),
                'motivo' => $request->input('motivo'),
                'descripcion' => $request->input('descripcion'),
                'compromisos' => $request->input('compromisos'),
                'remitido_a' => $request->input('remitido_a'),
                'estado' => 'Abierto'
            ];

            $seguimiento = Seguimiento::fromArray($datos);
            $this->seguimientoRepo->save($seguimiento);

            Session::flash('success', 'Seguimiento registrado correctamente.');
            Response::redirect(APP_URL . '/orientacion/seguimientos');

        } catch (\Exception $e) {
            Session::flash('error', 'Error al guardar seguimiento: ' . $e->getMessage());
            Response::redirect(APP_URL . '/orientacion/nuevo?id_estudiante=' . $request->input('id_estudiante'));
        }
    }

    /**
     * Ver historial de un estudiante
     */
    public function historial(): void
    {
        $request = new Request();
        $id = (int) $request->query('id');
        $estudiante = $this->estudianteRepo->findById($id);
        
        if (!$estudiante) {
            Response::error(404, 'Estudiante no encontrado');
            return;
        }

        $data = [
            'estudiante' => $estudiante,
            'historial' => $this->seguimientoRepo->findByEstudiante($id)
        ];
        Response::view('orientacion.historial', $data);
    }
}
