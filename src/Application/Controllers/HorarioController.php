<?php

namespace App\Application\Controllers;

use App\Core\{Request, Response, Session, Database};
use App\Domain\Services\HorarioService;
use App\Infrastructure\Repositories\MySQLCursoRepository;

class HorarioController
{
    private HorarioService $horarioService;
    private MySQLCursoRepository $cursoRepo;

    public function __construct()
    {
        $this->horarioService = new HorarioService();
        $this->cursoRepo = new MySQLCursoRepository();
    }

    /**
     * Muestra la cuadrícula del horario de un curso
     */
    public function ver(): void
    {
        $request = new Request();
        $cursoId = (int)$request->query('curso_id');
        
        if (!$cursoId) {
            Session::flash('error', 'Debe especificar un curso válido.');
            Response::redirect(APP_URL . '/cursos');
            return;
        }

        $curso = $this->cursoRepo->findById($cursoId);
        if (!$curso) {
            Session::flash('error', 'Curso no encontrado.');
            Response::redirect(APP_URL . '/cursos');
            return;
        }

        $horario = $this->horarioService->obtenerHorarioCurso($cursoId);
        $materias = $this->horarioService->obtenerMateriasPorCurso($curso['grado']);
        $profesores = $this->horarioService->obtenerProfesores();

        Response::view('horarios.view', [
            'curso' => $curso,
            'horario' => $horario,
            'materias' => $materias,
            'profesores' => $profesores
        ]);
    }

    /**
     * Endpoint API para guardar un bloque del horario (Llamada AJAX)
     */
    public function guardarBloque(): void
    {
        $request = new Request();
        // Asegurar que es petición POST y JSON (básico)
        $data = json_decode(file_get_contents('php://input'), true);
        
        $cursoId = (int)($data['curso_id'] ?? 0);
        $dia = $data['dia'] ?? '';
        $bloque = (int)($data['bloque'] ?? 0);
        $materiaId = !empty($data['materia_id']) ? (int)$data['materia_id'] : null;
        $profesorId = !empty($data['profesor_id']) ? (int)$data['profesor_id'] : null;

        if (!$cursoId || !$dia || !$bloque) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
            return;
        }

        try {
            $result = $this->horarioService->guardarBloque($cursoId, $dia, $bloque, $materiaId, $profesorId);
            header('Content-Type: application/json');
            echo json_encode(['success' => $result]);
        } catch (\Exception $e) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
