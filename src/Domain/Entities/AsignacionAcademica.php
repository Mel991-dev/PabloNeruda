<?php

namespace App\Domain\Entities;

/**
 * Entidad AsignacionAcademica
 * Representa la asignación de un profesor a una materia en un curso específico.
 */
class AsignacionAcademica
{
    private ?int $id_asignacion = null;
    private int $fk_profesor;
    private int $fk_materia;
    private int $fk_curso;

    public function getIdAsignacion(): ?int
    {
        return $this->id_asignacion;
    }

    public function setIdAsignacion(?int $id): self
    {
        $this->id_asignacion = $id;
        return $this;
    }

    public function getFkProfesor(): int
    {
        return $this->fk_profesor;
    }

    public function setFkProfesor(int $id): self
    {
        $this->fk_profesor = $id;
        return $this;
    }

    public function getFkMateria(): int
    {
        return $this->fk_materia;
    }

    public function setFkMateria(int $id): self
    {
        $this->fk_materia = $id;
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

    public static function fromArray(array $data): self
    {
        $asignacion = new self();
        $asignacion->setIdAsignacion($data['id_asignacion'] ?? null)
                   ->setFkProfesor($data['fk_profesor'] ?? 0)
                   ->setFkMateria($data['fk_materia'] ?? 0)
                   ->setFkCurso($data['fk_curso'] ?? 0);
        return $asignacion;
    }
}
