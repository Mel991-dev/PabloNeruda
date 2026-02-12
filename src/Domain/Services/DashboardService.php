<?php

namespace App\Domain\Services;

use App\Core\Database;

class DashboardService
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function obtenerEstadisticasAdmin(): array
    {
        return [
            'total_estudiantes' => $this->contar('estudiantes', "WHERE estado = 'Activo'"),
            'total_profesores' => $this->contar('profesores', "WHERE estado = 'Activo'"),
            'total_cursos' => $this->contar('cursos'),
            'usuarios_activos' => $this->contar('usuarios', "WHERE estado = 'Activo'"),
            'avanzadas' => $this->obtenerEstadisticasAvanzadas()
        ];
    }

    public function obtenerEstadisticasAvanzadas(): array
    {
        // 1. Materias con mayor reprobación
        $sqlReprobacion = "SELECT 
                            m.nombre as materia,
                            COUNT(n.id_nota) as total_notas,
                            SUM(CASE WHEN n.estado = 'Reprobado' THEN 1 ELSE 0 END) as total_reprobados
                           FROM notas n
                           INNER JOIN materias m ON n.fk_materia = m.id_materia
                           INNER JOIN matriculas mat ON n.fk_matricula = mat.id_matricula
                           WHERE mat.estado = 'Activo'
                           GROUP BY m.id_materia
                           HAVING total_reprobados > 0
                           ORDER BY total_reprobados DESC
                           LIMIT 5";
        
        $stmtRep = $this->db->query($sqlReprobacion);
        $materiasReprobacion = $stmtRep->fetchAll(\PDO::FETCH_ASSOC);

        // 2. Distribución de notas (Rendimiento)
        // Superior: 4.6 - 5.0
        // Alto: 4.0 - 4.5
        // Básico: 3.0 - 3.9
        // Bajo: 0.0 - 2.9
        $sqlDistribucion = "SELECT 
                                SUM(CASE WHEN promedio_periodo >= 4.6 THEN 1 ELSE 0 END) as superior,
                                SUM(CASE WHEN promedio_periodo >= 4.0 AND promedio_periodo <= 4.5 THEN 1 ELSE 0 END) as alto,
                                SUM(CASE WHEN promedio_periodo >= 3.0 AND promedio_periodo <= 3.9 THEN 1 ELSE 0 END) as basico,
                                SUM(CASE WHEN promedio_periodo < 3.0 THEN 1 ELSE 0 END) as bajo
                            FROM notas n
                            INNER JOIN matriculas mat ON n.fk_matricula = mat.id_matricula
                            WHERE mat.estado = 'Activo' AND n.promedio_periodo IS NOT NULL";
        
        $stmtDist = $this->db->query($sqlDistribucion);
        $distribucion = $stmtDist->fetch(\PDO::FETCH_ASSOC);

        return [
            'materias_reprobacion' => $materiasReprobacion,
            'distribucion_notas' => $distribucion
        ];
    }

    public function obtenerEstadisticasProfesor(int $profesorId): array
    {
        // Contar cursos distintos asignados al profesor
        $sqlCursos = "SELECT COUNT(DISTINCT fk_curso) FROM profesor_materia_curso WHERE fk_profesor = :profesor_id";
        $stmtCursos = $this->db->prepare($sqlCursos);
        $stmtCursos->execute(['profesor_id' => $profesorId]);
        $totalCursos = (int) $stmtCursos->fetchColumn();

        // Contar estudiantes totales en esos cursos
        $sqlEst = "SELECT COUNT(DISTINCT m.fk_estudiante) 
                   FROM matriculas m
                   INNER JOIN profesor_materia_curso pmc ON m.fk_curso = pmc.fk_curso
                   WHERE pmc.fk_profesor = :profesor_id AND m.estado = 'Activo'";
        $stmtEst = $this->db->prepare($sqlEst);
        $stmtEst->execute(['profesor_id' => $profesorId]);
        $totalEstudiantes = (int) $stmtEst->fetchColumn();

        return [
            'total_cursos' => $totalCursos,
            'total_estudiantes' => $totalEstudiantes
        ];
    }

    public function obtenerEstadisticasCoordinador(): array
    {
        return [
            'total_estudiantes' => $this->contar('estudiantes', "WHERE estado = 'Activo'"),
            'estudiantes_alergias' => $this->contar('estudiantes', "WHERE tiene_alergias = 1 AND estado = 'Activo'"),
            'matriculas_activas' => $this->contar('matriculas', "WHERE estado = 'Activo'")
        ];
    }

    public function obtenerEstadisticasRector(): array
    {
        // Estadísticas generales y de rendimiento
        return [
            'total_estudiantes' => $this->contar('estudiantes', "WHERE estado = 'Activo'"),
            'total_aprobados' => $this->contar('notas', "WHERE estado = 'Aprobado'"),
            'total_reprobados' => $this->contar('notas', "WHERE estado = 'Reprobado'"),
            'estudiantes_alergias' => $this->contar('estudiantes', "WHERE tiene_alergias = 1 AND estado = 'Activo'")
        ];
    }

    public function obtenerEstadisticasOrientador(): array
    {
        return [
            'total_seguimientos' => $this->contar('seguimientos_orientacion'),
            'casos_abiertos' => $this->contar('seguimientos_orientacion', "WHERE estado = 'Abierto'"),
            'alertas_academicas' => $this->contar('v_estudiantes_riesgo_academico')
        ];
    }

    private function contar(string $tabla, string $where = ''): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM $tabla $where");
        return (int) $stmt->fetchColumn();
    }
}
