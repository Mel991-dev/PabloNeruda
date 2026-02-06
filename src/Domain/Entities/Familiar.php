<?php

namespace App\Domain\Entities;

class Familiar
{
    private ?int $id_familiar = null;
    private string $nombre;
    private string $apellido;
    private string $tipo_documento = 'CC';
    private ?string $numero_documento = null;
    private ?string $ocupacion = null;
    private ?string $empresa = null;
    private ?string $nivel_educativo = null;
    private ?string $telefono = null;
    private ?string $direccion = null;
    private ?string $barrio = null;
    private ?string $email = null;
    private bool $vive_con_estudiante = false;

    // Getters
    public function getId(): ?int { return $this->id_familiar; }
    public function getNombre(): string { return $this->nombre; }
    public function getApellido(): string { return $this->apellido; }
    public function getNombreCompleto(): string { return $this->nombre . ' ' . $this->apellido; }
    public function getTipoDocumento(): string { return $this->tipo_documento; }
    public function getNumeroDocumento(): ?string { return $this->numero_documento; }
    public function getOcupacion(): ?string { return $this->ocupacion; }
    public function getEmpresa(): ?string { return $this->empresa; }
    public function getNivelEducativo(): ?string { return $this->nivel_educativo; }
    public function getTelefono(): ?string { return $this->telefono; }
    public function getDireccion(): ?string { return $this->direccion; }
    public function getBarrio(): ?string { return $this->barrio; }
    public function getEmail(): ?string { return $this->email; }
    public function viveConEstudiante(): bool { return $this->vive_con_estudiante; }

    // Setters
    public function setId(?int $id): self { $this->id_familiar = $id; return $this; }
    public function setNombre(string $v): self { $this->nombre = trim($v); return $this; }
    public function setApellido(string $v): self { $this->apellido = trim($v); return $this; }
    public function setTipoDocumento(string $v): self { $this->tipo_documento = $v; return $this; }
    public function setNumeroDocumento(?string $v): self { $this->numero_documento = $v; return $this; }
    public function setOcupacion(?string $v): self { $this->ocupacion = $v; return $this; }
    public function setEmpresa(?string $v): self { $this->empresa = $v; return $this; }
    public function setNivelEducativo(?string $v): self { $this->nivel_educativo = $v; return $this; }
    public function setTelefono(?string $v): self { $this->telefono = $v; return $this; }
    public function setDireccion(?string $v): self { $this->direccion = $v; return $this; }
    public function setBarrio(?string $v): self { $this->barrio = $v; return $this; }
    public function setEmail(?string $v): self { $this->email = $v; return $this; }
    public function setViveConEstudiante(bool $v): self { $this->vive_con_estudiante = $v; return $this; }

    public static function fromArray(array $data): self
    {
        $obj = new self();
        if (isset($data['id_familiar'])) $obj->setId((int)$data['id_familiar']);
        
        $obj->setNombre($data['nombre'] ?? '')
            ->setApellido($data['apellido'] ?? '')
            ->setTipoDocumento($data['tipo_documento'] ?? 'CC')
            ->setNumeroDocumento($data['numero_documento'] ?? null)
            ->setOcupacion($data['ocupacion'] ?? null)
            ->setEmpresa($data['empresa'] ?? null)
            ->setNivelEducativo($data['nivel_educativo'] ?? null)
            ->setTelefono($data['telefono'] ?? null)
            ->setDireccion($data['direccion'] ?? null)
            ->setBarrio($data['barrio'] ?? null)
            ->setEmail($data['email'] ?? null)
            ->setViveConEstudiante((bool)($data['vive_con_estudiante'] ?? false));
            
        return $obj;
    }
}
