<?php

namespace App\Domain\Entities;

class InfoSalud
{
    private ?int $id_info_salud = null;
    private int $fk_estudiante;
    private ?string $eps = null;
    private ?string $tipo_sangre = null;
    private ?string $limitaciones_fisicas = null;
    private ?string $limitaciones_sensoriales = null;
    private ?string $medicamentos_permanentes = null;
    private bool $vacunas_completas = false;
    private bool $toma_medicamentos = false;
    private ?string $alergico_a = null;
    private ?string $dificultad_salud = null;

    // Getters
    public function getId(): ?int { return $this->id_info_salud; }
    public function getFkEstudiante(): int { return $this->fk_estudiante; }
    public function getEps(): ?string { return $this->eps; }
    public function getTipoSangre(): ?string { return $this->tipo_sangre; }
    public function getLimitacionesFisicas(): ?string { return $this->limitaciones_fisicas; }
    public function getLimitacionesSensoriales(): ?string { return $this->limitaciones_sensoriales; }
    public function getMedicamentosPermanentes(): ?string { return $this->medicamentos_permanentes; }
    public function areVacunasCompletas(): bool { return $this->vacunas_completas; }
    public function tomaMedicamentos(): bool { return $this->toma_medicamentos; }
    public function getAlergicoA(): ?string { return $this->alergico_a; }
    public function getDificultadSalud(): ?string { return $this->dificultad_salud; }

    // Setters
    public function setId(?int $id): self { $this->id_info_salud = $id; return $this; }
    public function setFkEstudiante(int $id): self { $this->fk_estudiante = $id; return $this; }
    public function setEps(?string $val): self { $this->eps = $val; return $this; }
    public function setTipoSangre(?string $val): self { $this->tipo_sangre = $val; return $this; }
    public function setLimitacionesFisicas(?string $val): self { $this->limitaciones_fisicas = $val; return $this; }
    public function setLimitacionesSensoriales(?string $val): self { $this->limitaciones_sensoriales = $val; return $this; }
    public function setMedicamentosPermanentes(?string $val): self { $this->medicamentos_permanentes = $val; return $this; }
    public function setVacunasCompletas(bool $val): self { $this->vacunas_completas = $val; return $this; }
    public function setTomaMedicamentos(bool $val): self { $this->toma_medicamentos = $val; return $this; }
    public function setAlergicoA(?string $val): self { $this->alergico_a = $val; return $this; }
    public function setDificultadSalud(?string $val): self { $this->dificultad_salud = $val; return $this; }

    public static function fromArray(array $data): self
    {
        $obj = new self();
        if (isset($data['id_info_salud'])) $obj->setId((int)$data['id_info_salud']);
        if (isset($data['fk_estudiante'])) $obj->setFkEstudiante((int)$data['fk_estudiante']);
        $obj->setEps($data['eps'] ?? null)
            ->setTipoSangre($data['tipo_sangre'] ?? null)
            ->setLimitacionesFisicas($data['limitaciones_fisicas'] ?? null)
            ->setLimitacionesSensoriales($data['limitaciones_sensoriales'] ?? null)
            ->setMedicamentosPermanentes($data['medicamentos_permanentes'] ?? null)
            ->setVacunasCompletas((bool)($data['vacunas_completas'] ?? false))
            ->setTomaMedicamentos((bool)($data['toma_medicamentos'] ?? false))
            ->setAlergicoA($data['alergico_a'] ?? null)
            ->setDificultadSalud($data['dificultad_salud'] ?? null);
        return $obj;
    }
}
