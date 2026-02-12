<?php

namespace App\Domain\Services;

use App\Domain\Repositories\NotaRepositoryInterface;
use App\Domain\Repositories\EstudianteRepositoryInterface;
use App\Domain\Repositories\CursoRepositoryInterface;

class ReporteService
{
    private NotaRepositoryInterface $notaRepo;
    private EstudianteRepositoryInterface $estudianteRepo;
    private CursoRepositoryInterface $cursoRepo;

    public function __construct(
        NotaRepositoryInterface $notaRepo,
        EstudianteRepositoryInterface $estudianteRepo,
        CursoRepositoryInterface $cursoRepo
    ) {
        $this->notaRepo = $notaRepo;
        $this->estudianteRepo = $estudianteRepo;
        $this->cursoRepo = $cursoRepo;
    }

    /**
     * Generar Boletín de Calificaciones para un Estudiante
     */
    public function generarBoletin(int $estudianteId, int $periodo): array
    {
        // 1. Obtener datos del estudiante
        $estudiante = $this->estudianteRepo->findById($estudianteId);
        if (!$estudiante) {
            throw new \Exception("Estudiante no encontrado");
        }

        // 2. Obtener datos del curso (para saber el nombre del curso)
        // Necesitamos saber el curso actual del estudiante. 
        // Asumimos que repo de estudiante tiene metodo para traer curso, o buscamos matricula.
        // Por MVP, usaremos una consulta directa en repo o asumimos que tenemos el ID del curso.
        // Simplificación: Traemos las notas y de ahí sacamos materias.
        
        $notas = $this->notaRepo->findByEstudianteAndPeriodo($estudianteId, $periodo);
        
        // Calcular promedios generales
        $sumaPromedios = 0;
        $totalMaterias = count($notas);
        $materiasCalificadas = 0;
        $materiasReprobadas = 0;

        foreach ($notas as $nota) {
            // Solo imprimir promedio si existe nota
            if (isset($nota['promedio']) && $nota['promedio'] !== null) {
                $sumaPromedios += $nota['promedio'];
                $materiasCalificadas++;
                
                if ($nota['promedio'] < 3.0) {
                    $materiasReprobadas++;
                }
            }
        }

        $promedioGeneral = $materiasCalificadas > 0 ? ($sumaPromedios / $materiasCalificadas) : 0;

        return [
            'estudiante' => $estudiante,
            'periodo' => $periodo,
            'fecha_generacion' => date('Y-m-d H:i:s'),
            'notas' => $notas,
            'estadisticas' => [
                'promedio_general' => round($promedioGeneral, 2),
                'materias_reprobadas' => $materiasReprobadas,
                'total_materias' => $totalMaterias, // Total materias del grado
                'puesto' => 'N/A' // Pendiente cálculo de puesto
            ]
        ];
    }

    public function obtenerCursos(): array
    {
        return $this->cursoRepo->findAll();
    }

    public function obtenerEstudiantesPorCurso(int $cursoId): array
    {
        return $this->estudianteRepo->findByCurso($cursoId);
    }
}
