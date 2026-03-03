<?php

namespace App\Domain\Services;

use App\Core\Database;

class AuditService
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Registra un nuevo evento en el log de auditoría
     *
     * @param int $usuarioId
     * @param string $rolUsuario
     * @param string $accion (INSERT, UPDATE, DELETE, LOGIN, LOGOUT, OTHER)
     * @param string $modulo
     * @param string $detalles
     * @return bool
     */
    public function registrar(int $usuarioId, string $rolUsuario, string $accion, string $modulo, string $detalles): bool
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        
        $sql = "INSERT INTO log_auditoria (fk_usuario, rol_usuario, accion, modulo, detalles, ip_address) 
                VALUES (:usuario_id, :rol, :accion, :modulo, :detalles, :ip_address)";
                
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':rol'        => $rolUsuario,
            ':accion'     => $accion,
            ':modulo'     => $modulo,
            ':detalles'   => $detalles,
            ':ip_address' => $ipAddress
        ]);
    }

    /**
     * Obtiene la actividad reciente aplicando filtros
     *
     * @param array $filtros
     * @return array
     */
    public function obtenerActividadReciente(array $filtros = [], int $limit = 20): array
    {
        $sql = "SELECT l.*, u.username as nombre_usuario 
                FROM log_auditoria l
                LEFT JOIN usuarios u ON l.fk_usuario = u.id_usuario
                WHERE 1=1";
        
        $params = [];

        // Filtro por tipo de acción
        if (!empty($filtros['accion'])) {
            $sql .= " AND l.accion = :accion";
            $params[':accion'] = $filtros['accion'];
        }

        // Filtro por rol
        if (!empty($filtros['rol'])) {
            $sql .= " AND l.rol_usuario = :rol";
            $params[':rol'] = $filtros['rol'];
        }

        // Ordenamiento
        $orderDir = (isset($filtros['orden']) && $filtros['orden'] === 'ASC') ? 'ASC' : 'DESC';
        $sql .= " ORDER BY l.fecha " . $orderDir;
        
        $sql .= " LIMIT " . (int)$limit;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
