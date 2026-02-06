<?php

namespace App\Domain\Entities;

class InfoSocioeconomica
{
    private ?int $id_socioeconomico = null;
    private int $fk_estudiante;
    private ?string $sisben_nivel = null;
    private ?int $estrato = null;
    private ?string $barrio = null;
    private ?string $sector = null;
    private ?string $tipo_vivienda = null;
    private bool $tiene_internet = false;
    private bool $servicios_publicos_completo = true;
    private bool $victima_conflicto = false;
    private ?string $victima_conflicto_detalle = null;
    private ?string $grupo_etnico = 'Ninguno';
    private ?string $resguardo_indigena = null;
    private bool $familias_en_accion = false;
    private bool $poblacion_desplazada = false;
    private ?string $lugar_desplazamiento = null;

    // Getters
    public function getId(): ?int { return $this->id_socioeconomico; }
    public function getFkEstudiante(): int { return $this->fk_estudiante; }
    public function getSisbenNivel(): ?string { return $this->sisben_nivel; }
    public function getEstrato(): ?int { return $this->estrato; }
    public function getBarrio(): ?string { return $this->barrio; }
    public function getSector(): ?string { return $this->sector; }
    public function getTipoVivienda(): ?string { return $this->tipo_vivienda; }
    public function tieneInternet(): bool { return $this->tiene_internet; }
    public function tieneServiciosCompletos(): bool { return $this->servicios_publicos_completo; }
    public function esVictimaConflicto(): bool { return $this->victima_conflicto; }
    public function getDetalleVictima(): ?string { return $this->victima_conflicto_detalle; }
    public function getGrupoEtnico(): ?string { return $this->grupo_etnico; }
    public function getResguardo(): ?string { return $this->resguardo_indigena; }
    public function esFamiliasAccion(): bool { return $this->familias_en_accion; }
    public function esDesplazado(): bool { return $this->poblacion_desplazada; }
    public function getLugarDesplazamiento(): ?string { return $this->lugar_desplazamiento; }

    // Setters (Simplificado para builder pattern)
    public function setId(?int $id): self { $this->id_socioeconomico = $id; return $this; }
    public function setFkEstudiante(int $id): self { $this->fk_estudiante = $id; return $this; }
    public function setSisbenNivel(?string $v): self { $this->sisben_nivel = $v; return $this; }
    public function setEstrato(?int $v): self { $this->estrato = $v; return $this; }
    public function setBarrio(?string $v): self { $this->barrio = $v; return $this; }
    public function setSector(?string $v): self { $this->sector = $v; return $this; }
    public function setTipoVivienda(?string $v): self { $this->tipo_vivienda = $v; return $this; }
    public function setTieneInternet(bool $v): self { $this->tiene_internet = $v; return $this; }
    public function setServiciosCompletos(bool $v): self { $this->servicios_publicos_completo = $v; return $this; }
    public function setVictimaConflicto(bool $v): self { $this->victima_conflicto = $v; return $this; }
    public function setDetalleVictima(?string $v): self { $this->victima_conflicto_detalle = $v; return $this; }
    public function setGrupoEtnico(?string $v): self { $this->grupo_etnico = $v; return $this; }
    public function setResguardo(?string $v): self { $this->resguardo_indigena = $v; return $this; }
    public function setFamiliasAccion(bool $v): self { $this->familias_en_accion = $v; return $this; }
    public function setPoblacionDesplazada(bool $v): self { $this->poblacion_desplazada = $v; return $this; }
    public function setLugarDesplazamiento(?string $v): self { $this->lugar_desplazamiento = $v; return $this; }

    public static function fromArray(array $data): self
    {
        $obj = new self();
        if (isset($data['id_socioeconomico'])) $obj->setId((int)$data['id_socioeconomico']);
        if (isset($data['fk_estudiante'])) $obj->setFkEstudiante((int)$data['fk_estudiante']);
        
        $obj->setSisbenNivel($data['sisben_nivel'] ?? null)
            ->setEstrato(isset($data['estrato']) ? (int)$data['estrato'] : null)
            ->setBarrio($data['barrio'] ?? null)
            ->setSector($data['sector'] ?? null)
            ->setTipoVivienda($data['tipo_vivienda'] ?? null)
            ->setTieneInternet((bool)($data['tiene_internet'] ?? false))
            ->setServiciosCompletos((bool)($data['servicios_publicos_completo'] ?? true))
            ->setVictimaConflicto((bool)($data['victima_conflicto'] ?? false))
            ->setDetalleVictima($data['victima_conflicto_detalle'] ?? null)
            ->setGrupoEtnico($data['grupo_etnico'] ?? 'Ninguno')
            ->setResguardo($data['resguardo_indigena'] ?? null)
            ->setFamiliasAccion((bool)($data['familias_en_accion'] ?? false))
            ->setPoblacionDesplazada((bool)($data['poblacion_desplazada'] ?? false))
            ->setLugarDesplazamiento($data['lugar_desplazamiento'] ?? null);
            
        return $obj;
    }
}
