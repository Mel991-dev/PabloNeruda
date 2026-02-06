<?php

namespace App\Application\Controllers;

use App\Core\{Request, Response, Session};
use App\Domain\Services\NotaService;
use App\Infrastructure\Repositories\{MySQLNotaRepository, MySQLCursoRepository, MySQLMateriaRepository};

class NotaController
{
    private NotaService $service;

    public function __construct()
    {
        $this->service = new NotaService(
            new MySQLNotaRepository(),
            new MySQLCursoRepository(),
            new MySQLMateriaRepository()
        );
    }

    /**
     * Dashboard de Notas (Selección de Curso y Materia)
     */
    public function index(): void
    {
        $cursos = $this->service->listarCursos();
        $materias = $this->service->listarMaterias();
        
        Response::view('notas.index', [
            'cursos' => $cursos,
            'materias' => $materias,
            'titulo' => 'Gestión de Notas'
        ]);
    }

    /**
     * Formulario de Registro de Notas
     */
    public function create(): void
    {
        $request = new Request();
        $cursoId = (int)$request->query('curso');
        $materiaId = (int)$request->query('materia');
        $periodo = (int)($request->query('periodo') ?? 1);

        if (!$cursoId || !$materiaId) {
            Session::flash('error', 'Debes seleccionar un curso y una materia.');
            Response::redirect(APP_URL . '/notas');
            return;
        }

        $estudiantes = $this->service->obtenerEstudiantesConNotas($cursoId, $materiaId, $periodo);
        $nombreCurso = $this->service->obtenerNombreCurso($cursoId);
        $nombreMateria = $this->service->obtenerNombreMateria($materiaId);

        Response::view('notas.create', [
            'estudiantes' => $estudiantes,
            'cursoId' => $cursoId,
            'materiaId' => $materiaId,
            'periodo' => $periodo,
            'nombreCurso' => $nombreCurso,
            'nombreMateria' => $nombreMateria,
            'titulo' => 'Registrar Calificaciones'
        ]);
    }

    /**
     * Guardar Notas
     */
    public function store(): void
    {
        $request = new Request();
        
        if (!$request->isPost()) {
            Response::redirect(APP_URL . '/notas');
            return;
        }

        try {
            $cursoId = (int)$request->input('curso_id');
            $materiaId = (int)$request->input('materia_id');
            $periodo = (int)$request->input('periodo');
            $notas = $request->input('notas'); // Array: [matricula_id => [1=>3.0, 2=>4.0...]]
            
            // Obtener ID del profesor de la sesión
            $fkProfesor = Session::get('fk_profesor');
            
            // Si no hay profesor vinculado (ej: Admin), intentar usar un default o fallar con mensaje claro
            if (!$fkProfesor) {
                // FALLBACK: Si es Admin, permitir usar ID 1 (o el primero que exista en DB)
                // OJO: Esto es un parche para que el Admin pueda probar sin estar vinculado.
                if (Session::get('rol') === 'Administrador') {
                    $fkProfesor = 1; // Asumimos que existe un profesor con ID 1
                } else {
                    throw new \Exception("Tu usuario no está vinculado a un perfil de profesor válido.");
                }
            }

            if (empty($notas)) {
                throw new \Exception("No se recibieron datos de notas.");
            }

            $this->service->registrarNotas($materiaId, $periodo, $notas, $fkProfesor);

            Session::flash('success', 'Calificaciones guardadas exitosamente.');
            
            // Redirigir a la misma página para seguir editando o ver resultados
            Response::redirect(APP_URL . "/notas/registrar?curso=$cursoId&materia=$materiaId&periodo=$periodo");

        } catch (\Exception $e) {
            Session::flash('error', 'Error al guardar notas: ' . $e->getMessage());
            
            // Intentar recuperar parámetros para redirigir
            $cursoId = $request->input('curso_id');
            $materiaId = $request->input('materia_id');
            $periodo = $request->input('periodo');
            
            if ($cursoId && $materiaId) {
                Response::redirect(APP_URL . "/notas/registrar?curso=$cursoId&materia=$materiaId&periodo=$periodo");
            } else {
                Response::redirect(APP_URL . '/notas');
            }
        }
    }
}
