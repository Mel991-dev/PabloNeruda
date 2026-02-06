<?php

namespace App\Domain\Entities;

/**
 * Entidad Materia
 * Representa una asignatura académica en la institución.
 */
class Materia
{
    private ?int $id_materia = null;
    private string $nombre;
    private string $grado_aplicable; // Preescolar, 1°, 2°, 3°, 4°, 5°, Todos
    private int $intensidad_horaria;
    private ?string $descripcion = null;

    public function getIdMateria(): ?int
    {
        return $this->id_materia;
    }

    public function getId(): ?int
    {
        return $this->id_materia;
    }

    public function setIdMateria(?int $id): self
    {
        $this->id_materia = $id;
        return $this;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getGradoAplicable(): string
    {
        return $this->grado_aplicable;
    }

    public function setGradoAplicable(string $grado): self
    {
        $this->grado_aplicable = $grado;
        return $this;
    }

    public function getIntensidadHoraria(): int
    {
        return $this->intensidad_horaria;
    }

    public function setIntensidadHoraria(int $intensidad): self
    {
        $this->intensidad_horaria = $intensidad;
        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    public static function fromArray(array $data): self
    {
        $materia = new self();
        $materia->setIdMateria($data['id_materia'] ?? null)
                ->setNombre($data['nombre'] ?? '')
                ->setGradoAplicable($data['grado_aplicable'] ?? 'Todos')
                ->setIntensidadHoraria($data['intensidad_horaria'] ?? 0)
                ->setDescripcion($data['descripcion'] ?? null);
        return $materia;
    }
}
