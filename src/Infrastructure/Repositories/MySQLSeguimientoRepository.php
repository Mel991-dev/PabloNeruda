<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Seguimiento;
use App\Domain\Repositories\SeguimientoRepositoryInterface;
use App\Core\Database;
use PDO;

/**
 * Implementación MySQL para Seguimientos de Orientación
 */
class MySQLSeguimientoRepository implements SeguimientoRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function save(Seguimiento $seguimiento): bool
    {
        $sql = "INSERT INTO seguimientos_orientacion (
                    fk_estudiante, fk_usuario_orientador, tipo_intervencion, 
                    motivo, descripcion, compromisos, remitido_a, estado
                ) VALUES (
                    :est, :ori, :tipo, :motivo, :desc, :comp, :rem, :estado
                )";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'est' => $seguimiento->getEstudianteId(),
            'ori' => $seguimiento->getOrientadorId(),
            'tipo' => $seguimiento->getTipo(),
            'motivo' => $seguimiento->getMotivo(),
            'desc' => $seguimiento->getDescripcion(),
            'comp' => $seguimiento->getCompromisos(),
            'rem' => $seguimiento->getRemitidoA(),
            'estado' => $seguimiento->getEstado()
        ]);
    }

    public function findById(int $id): ?Seguimiento
    {
        $stmt = $this->db->prepare("SELECT * FROM seguimientos_orientacion WHERE id_seguimiento = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? Seguimiento::fromArray($row) : null;
    }

    public function findByEstudiante(int $idEstudiante): array
    {
        $sql = "SELECT s.*, u.username as nombre_orientador 
                FROM seguimientos_orientacion s
                JOIN usuarios u ON s.fk_usuario_orientador = u.id_usuario
                WHERE fk_estudiante = ? 
                ORDER BY fecha_seguimiento DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idEstudiante]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll(int $limit = 100): array
    {
        $sql = "SELECT s.*, e.nombre, e.apellido, u.username as orientador
                FROM seguimientos_orientacion s
                JOIN estudiantes e ON s.fk_estudiante = e.id_estudiante
                JOIN usuarios u ON s.fk_usuario_orientador = u.id_usuario
                ORDER BY s.fecha_seguimiento DESC LIMIT $limit";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(Seguimiento $seguimiento): bool
    {
        $sql = "UPDATE seguimientos_orientacion SET 
                    tipo_intervencion = :tipo, motivo = :motivo, 
                    descripcion = :desc, compromisos = :comp, 
                    remitido_a = :rem, estado = :estado,
                    fecha_cierre = :cierre
                WHERE id_seguimiento = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'tipo' => $seguimiento->getTipo(),
            'motivo' => $seguimiento->getMotivo(),
            'desc' => $seguimiento->getDescripcion(),
            'comp' => $seguimiento->getCompromisos(),
            'rem' => $seguimiento->getRemitidoA(),
            'estado' => $seguimiento->getEstado(),
            'cierre' => $seguimiento->getFechaCierre(),
            'id' => $seguimiento->getId()
        ]);
    }

    public function getAlertasAcademicas(): array
    {
        // Usar la vista creada en la migración
        return $this->db->query("SELECT * FROM v_estudiantes_riesgo_academico ORDER BY materias_perdidas DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAlertasVulnerabilidad(): array
    {
        return $this->db->query("SELECT * FROM v_estudiantes_vulnerabilidad")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerEstadisticas(): array
    {
        return [
            'total_seguimientos' => $this->db->query("SELECT COUNT(*) FROM seguimientos_orientacion")->fetchColumn(),
            'casos_abiertos' => $this->db->query("SELECT COUNT(*) FROM seguimientos_orientacion WHERE estado = 'Abierto'")->fetchColumn(),
            'alertas_academicas' => $this->db->query("SELECT COUNT(*) FROM v_estudiantes_riesgo_academico")->fetchColumn()
        ];
    }
}
