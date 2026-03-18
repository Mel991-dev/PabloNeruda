<?php

namespace App\Domain\Services;

use App\Core\Database;

class HorarioService
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtiene el horario completo de un curso estructurado por día y bloque.
     */
    public function obtenerHorarioCurso(int $cursoId): array
    {
        $sql = "SELECT h.id_horario, h.dia_semana, h.bloque_hora, 
                       h.fk_materia, m.nombre as materia_nombre, 
                       h.fk_profesor, CONCAT(p.nombre, ' ', p.apellido) as profesor_nombre
                FROM horarios h
                LEFT JOIN materias m ON h.fk_materia = m.id_materia
                LEFT JOIN profesores p ON h.fk_profesor = p.id_profesor
                WHERE h.fk_curso = :cursoId
                ORDER BY FIELD(h.dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'), h.bloque_hora";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['cursoId' => $cursoId]);
        $resultados = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Estructurar en matriz [dia][bloque] para facilitar la vista
        $matriz = [];
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        foreach ($dias as $dia) {
            for ($b = 1; $b <= 6; $b++) {
                $matriz[$dia][$b] = null; // Inicializar vacío
            }
        }

        foreach ($resultados as $row) {
            $matriz[$row['dia_semana']][$row['bloque_hora']] = $row;
        }

        return $matriz;
    }

    /**
     * Obtiene el horario completo de un profesor estructurado por día y bloque.
     */
    public function obtenerHorarioProfesor(int $fkProfesor): array
    {
        $sql = "SELECT h.dia_semana, h.bloque_hora, 
                       m.nombre as materia_nombre, 
                       CONCAT(c.grado, ' - ', c.seccion) as curso_nombre
                FROM horarios h
                LEFT JOIN materias m ON h.fk_materia = m.id_materia
                LEFT JOIN cursos c ON h.fk_curso = c.id_curso
                WHERE h.fk_profesor = :profesorId
                ORDER BY FIELD(h.dia_semana, 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'), h.bloque_hora";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['profesorId' => $fkProfesor]);
        $resultados = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        // Estructurar en matriz [dia][bloque] para facilitar la vista
        $matriz = [];
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
        foreach ($dias as $dia) {
            for ($b = 1; $b <= 6; $b++) {
                $matriz[$dia][$b] = null; // Inicializar vacío
            }
        }

        foreach ($resultados as $row) {
            $matriz[$row['dia_semana']][$row['bloque_hora']] = $row;
        }

        return $matriz;
    }

    /**
     * Obtiene materias aplicables a un curso específico
     */
    public function obtenerMateriasPorCurso(string $grado): array
    {
        $sql = "SELECT id_materia, nombre FROM materias 
                WHERE grado_aplicable = :grado OR grado_aplicable = 'Todos'
                ORDER BY nombre";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['grado' => $grado]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Obtiene profesores activos
     */
    public function obtenerProfesores(): array
    {
        $sql = "SELECT id_profesor, CONCAT(nombre, ' ', apellido) as nombre_completo 
                FROM profesores WHERE estado = 'Activo' ORDER BY nombre";
        return $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Guarda o actualiza un bloque específico del horario
     */
    public function guardarBloque(int $cursoId, string $dia, int $bloque, ?int $materiaId, ?int $profesorId): bool
    {
        // Verificar si ya existe el bloque
        $sqlCheck = "SELECT id_horario FROM horarios WHERE fk_curso = ? AND dia_semana = ? AND bloque_hora = ?";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->execute([$cursoId, $dia, $bloque]);
        $existe = $stmtCheck->fetchColumn();

        if ($existe) {
            // Si mandan null en materia y profe, significa limpiar celda
            if (!$materiaId && !$profesorId) {
                $sqlDel = "DELETE FROM horarios WHERE id_horario = ?";
                return $this->db->prepare($sqlDel)->execute([$existe]);
            }
            
            // Actualizar
            $sqlUpd = "UPDATE horarios SET fk_materia = ?, fk_profesor = ? WHERE id_horario = ?";
            return $this->db->prepare($sqlUpd)->execute([$materiaId, $profesorId, $existe]);
        } else {
            // Insertar nuevo si hay datos
            if ($materiaId || $profesorId) {
                $sqlIns = "INSERT INTO horarios (fk_curso, dia_semana, bloque_hora, fk_materia, fk_profesor) 
                           VALUES (?, ?, ?, ?, ?)";
                return $this->db->prepare($sqlIns)->execute([$cursoId, $dia, $bloque, $materiaId, $profesorId]);
            }
        }
        return true;
    }
}
