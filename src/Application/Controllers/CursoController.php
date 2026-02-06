<?php

namespace App\Application\Controllers;

use App\Core\{Request, Response, Session};
use App\Infrastructure\Repositories\MySQLCursoRepository;
use App\Infrastructure\Repositories\MySQLProfesorRepository;
use App\Domain\Services\ReporteService;
use App\Infrastructure\Repositories\MySQLNotaRepository;
use App\Infrastructure\Repositories\MySQLEstudianteRepository;

class CursoController
{
    private MySQLCursoRepository $cursoRepo;
    private MySQLProfesorRepository $profesorRepo;

    public function __construct()
    {
        $this->cursoRepo = new MySQLCursoRepository();
        $this->profesorRepo = new MySQLProfesorRepository();
    }

    /**
     * Listado de Cursos (Vista Principal)
     */
    public function index(): void
    {
        $cursos = $this->cursoRepo->findAll();
        
        // Agrupar cursos por nivel/grado para mejor visualización
        $cursosAgrupados = [];
        foreach ($cursos as $curso) {
            $grado = $curso['grado'];
            $cursosAgrupados[$grado][] = $curso;
        }

        Response::view('cursos.index', [
            'cursosAgrupados' => $cursosAgrupados,
            'titulo' => 'Listado de Cursos'
        ]);
    }

    public function create(): void
    {
        $profesores = $this->profesorRepo->findAll();
        Response::view('cursos.create', [
            'profesores' => $profesores,
            'titulo' => 'Crear Curso'
        ]);
    }

    public function store(): void
    {
        $request = new Request();
        $data = $request->all();

        // Validaciones básicas
        if (empty($data['grado']) || empty($data['seccion']) || empty($data['anio'])) {
            // TODO: Manejar error
            Response::redirect(APP_URL . '/cursos/crear');
            return;
        }

        $curso = [
            'grado' => $data['grado'],
            'seccion' => $data['seccion'],
            'anio' => $data['anio'],
            'jornada' => $data['jornada'],
            'capacidad' => $data['capacidad'] ?? 35,
            'director_grupo' => $data['director_grupo'] ?? null
        ];

        $this->cursoRepo->save($curso);
        Response::redirect(APP_URL . '/cursos');
    }

    public function edit(): void
    {
        $request = new Request();
        $id = (int)$request->query('id');
        
        $curso = $this->cursoRepo->findById($id);
        if (!$curso) {
            Response::redirect(APP_URL . '/cursos');
            return;
        }

        $profesores = $this->profesorRepo->findAll();
        Response::view('cursos.edit', [
            'curso' => $curso,
            'profesores' => $profesores,
            'titulo' => 'Editar Curso'
        ]);
    }

    public function update(): void
    {
        $request = new Request();
        $data = $request->all();
        $id = (int)$request->input('id');

        if (!$id) {
            Response::redirect(APP_URL . '/cursos');
            return;
        }

        $curso = [
            'id' => $id,
            'grado' => $data['grado'],
            'seccion' => $data['seccion'],
            'anio' => $data['anio'],
            'jornada' => $data['jornada'],
            'capacidad' => $data['capacidad'],
            'director_grupo' => $data['director_grupo'] ?? null
        ];

        $this->cursoRepo->update($curso);
        Response::redirect(APP_URL . '/cursos');
    }
    
    /**
     * Ver Estudiantes de un Curso
     */
    public function verEstudiantes(): void
    {
        $request = new Request();
        $cursoId = (int)$request->query('id');
        
        if (!$cursoId) {
            Response::redirect(APP_URL . '/cursos');
            return;
        }

        $reporteService = new ReporteService(
             new MySQLNotaRepository(),
             new MySQLEstudianteRepository(),
             new MySQLCursoRepository()
        );
        
        $estudiantes = $reporteService->obtenerEstudiantesPorCurso($cursoId);
        $curso = $this->cursoRepo->findById($cursoId);
        
        Response::view('cursos.estudiantes', [
            'estudiantes' => $estudiantes,
            'curso' => $curso,
            'titulo' => 'Estudiantes - ' . ($curso['grado'] ?? '') . ' ' . ($curso['seccion'] ?? '')
        ]);
    }
}
