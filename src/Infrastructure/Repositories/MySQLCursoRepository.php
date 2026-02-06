<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\CursoRepositoryInterface;
use App\Core\Database;
use PDO;

class MySQLCursoRepository implements CursoRepositoryInterface
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll(): array
    {
        $sql = "SELECT c.*, 
                (SELECT COUNT(*) FROM matriculas m WHERE m.fk_curso = c.id_curso AND m.estado = 'Activo') as total_estudiantes,
                (c.capacidad_maxima - (SELECT COUNT(*) FROM matriculas m WHERE m.fk_curso = c.id_curso AND m.estado = 'Activo')) as cupos_disponibles,
                p.nombre as director_nombre, p.apellido as director_apellido
                FROM cursos c
                LEFT JOIN profesores p ON c.director_grupo = p.id_profesor
                ORDER BY c.año_lectivo DESC, c.grado, c.seccion";
                
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id): ?array
    {
        $sql = "SELECT * FROM cursos WHERE id_curso = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function save(array $curso): void
    {
        $sql = "INSERT INTO cursos (grado, seccion, año_lectivo, jornada, capacidad_maxima, director_grupo) 
                VALUES (:grado, :seccion, :anio, :jornada, :capacidad, :director)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'grado' => $curso['grado'],
            'seccion' => $curso['seccion'],
            'anio' => $curso['anio'],
            'jornada' => $curso['jornada'],
            'capacidad' => $curso['capacidad'],
            'director' => !empty($curso['director_grupo']) ? $curso['director_grupo'] : null
        ]);
    }

    public function update(array $curso): void
    {
        $sql = "UPDATE cursos SET 
                grado = :grado,
                seccion = :seccion,
                año_lectivo = :anio,
                jornada = :jornada,
                capacidad_maxima = :capacidad,
                director_grupo = :director
                WHERE id_curso = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'grado' => $curso['grado'],
            'seccion' => $curso['seccion'],
            'anio' => $curso['anio'],
            'jornada' => $curso['jornada'],
            'capacidad' => $curso['capacidad'],
            'director' => !empty($curso['director_grupo']) ? $curso['director_grupo'] : null,
            'id' => $curso['id']
        ]);
    }
}
