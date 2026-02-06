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
            'usuarios_activos' => $this->contar('usuarios', "WHERE estado = 'Activo'")
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
