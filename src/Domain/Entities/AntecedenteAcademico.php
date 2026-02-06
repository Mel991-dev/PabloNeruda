<?php

namespace App\Domain\Entities;

class AntecedenteAcademico
{
    private ?int $id_antecedente = null;
    private int $fk_estudiante;
    private ?string $nivel_educativo = null;
    private ?string $institucion = null;
    private ?string $años_cursados = null;
    private ?string $motivo_retiro = null;
    private ?string $observaciones = null;

    // Getters
    public function getId(): ?int { return $this->id_antecedente; }
    public function getFkEstudiante(): int { return $this->fk_estudiante; }
    public function getNivelEducativo(): ?string { return $this->nivel_educativo; }
    public function getInstitucion(): ?string { return $this->institucion; }
    public function getAniosCursados(): ?string { return $this->años_cursados; }
    public function getMotivoRetiro(): ?string { return $this->motivo_retiro; }
    public function getObservaciones(): ?string { return $this->observaciones; }

    // Setters
    public function setId(?int $id): self { $this->id_antecedente = $id; return $this; }
    public function setFkEstudiante(int $id): self { $this->fk_estudiante = $id; return $this; }
    public function setNivelEducativo(?string $v): self { $this->nivel_educativo = $v; return $this; }
    public function setInstitucion(?string $v): self { $this->institucion = $v; return $this; }
    public function setAniosCursados(?string $v): self { $this->años_cursados = $v; return $this; }
    public function setMotivoRetiro(?string $v): self { $this->motivo_retiro = $v; return $this; }
    public function setObservaciones(?string $v): self { $this->observaciones = $v; return $this; }

    public static function fromArray(array $data): self
    {
        $obj = new self();
        if (isset($data['id_antecedente'])) $obj->setId((int)$data['id_antecedente']);
        if (isset($data['fk_estudiante'])) $obj->setFkEstudiante((int)$data['fk_estudiante']);

        $obj->setNivelEducativo($data['nivel_educativo'] ?? null)
            ->setInstitucion($data['institucion'] ?? null)
            ->setAniosCursados($data['años_cursados'] ?? null)
            ->setMotivoRetiro($data['motivo_retiro'] ?? null)
            ->setObservaciones($data['observaciones'] ?? null);
            
        return $obj;
    }
}
