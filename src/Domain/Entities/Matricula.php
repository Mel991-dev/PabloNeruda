<?php

namespace App\Domain\Entities;

/**
 * Entidad Matricula
 * Representa la inscripción de un estudiante en un curso para un año lectivo específico.
 */
class Matricula
{
    private ?int $id_matricula = null;
    private int $fk_estudiante;
    private int $fk_curso;
    private int $año_lectivo;
    private string $fecha_matricula;
    private string $estado; // Activo, Retirado, Anulado

    public function getIdMatricula(): ?int
    {
        return $this->id_matricula;
    }

    public function setIdMatricula(?int $id): self
    {
        $this->id_matricula = $id;
        return $this;
    }

    public function getFkEstudiante(): int
    {
        return $this->fk_estudiante;
    }

    public function setFkEstudiante(int $id): self
    {
        $this->fk_estudiante = $id;
        return $this;
    }

    public function getFkCurso(): int
    {
        return $this->fk_curso;
    }

    public function setFkCurso(int $id): self
    {
        $this->fk_curso = $id;
        return $this;
    }

    public function getAñoLectivo(): int
    {
        return $this->año_lectivo;
    }

    public function setAñoLectivo(int $anio): self
    {
        $this->año_lectivo = $anio;
        return $this;
    }

    public function getFechaMatricula(): string
    {
        return $this->fecha_matricula;
    }

    public function setFechaMatricula(string $fecha): self
    {
        $this->fecha_matricula = $fecha;
        return $this;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function setEstado(string $estado): self
    {
        $this->estado = $estado;
        return $this;
    }

    public static function fromArray(array $data): self
    {
        $matricula = new self();
        $matricula->setIdMatricula($data['id_matricula'] ?? null)
                  ->setFkEstudiante($data['fk_estudiante'] ?? 0)
                  ->setFkCurso($data['fk_curso'] ?? 0)
                  ->setAñoLectivo($data['año_lectivo'] ?? (int)date('Y'))
                  ->setFechaMatricula($data['fecha_matricula'] ?? date('Y-m-d'))
                  ->setEstado($data['estado'] ?? 'Activo');
        return $matricula;
    }
}
