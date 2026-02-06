<?php

namespace App\Application\Controllers;

use App\Core\{Request, Response, Session};
use App\Domain\Services\ReporteService;
use App\Infrastructure\Repositories\{MySQLNotaRepository, MySQLEstudianteRepository, MySQLCursoRepository};

class ReporteController
{
    private ReporteService $service;

    public function __construct()
    {
        $this->service = new ReporteService(
            new MySQLNotaRepository(),
            new MySQLEstudianteRepository(),
            new MySQLCursoRepository()
        );
    }

    /**
     * Dashboard de Reportes
     */
    public function index(): void
    {
        $cursos = $this->service->obtenerCursos();
        
        Response::view('reportes.index', [
            'cursos' => $cursos,
            'titulo' => 'Generación de Reportes'
        ]);
    }

    /**
     * Ver Boletín Individual (Vista previa o Impresión)
     */
    public function boletin(): void
    {
        $request = new Request();
        $estudianteId = (int)$request->query('estudiante_id');
        $periodo = (int)$request->query('periodo');

        if (!$estudianteId || !$periodo) {
            Session::flash('error', 'Faltan parámetros para generar el reporte.');
            Response::redirect(APP_URL . '/reportes');
            return;
        }

        try {
            $boletin = $this->service->generarBoletin($estudianteId, $periodo);
            
            // Usamos una vista 'limpia' para el boletín
            Response::view('reportes.boletin', [
                'data' => $boletin,
                'titulo' => 'Boletín Académico'
            ]);

        } catch (\Exception $e) {
            Session::flash('error', $e->getMessage());
            Response::redirect(APP_URL . '/reportes');
        }
    }
    
    /**
     * API: Obtener lista de estudiantes por curso (AJAX)
     */
    public function apiEstudiantes(): void
    {
        $request = new Request();
        $cursoId = (int)$request->query('curso_id');
        
        if (!$cursoId) {
            Response::json(['error' => 'Curso ID requerido'], 400);
            return;
        }
        
        $estudiantes = $this->service->obtenerEstudiantesPorCurso($cursoId);
        
        // Convertir objetos a array simple para JSON
        $data = array_map(function($est) {
            return [
                'id' => $est->getIdEstudiante(),
                'nombre' => $est->getNombreCompleto(),
                'documento' => $est->getNumeroDocumento()
            ];
        }, $estudiantes);
        
        Response::json($data);
    }
}
