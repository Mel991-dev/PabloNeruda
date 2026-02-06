<?php

namespace App\Domain\Entities;

/**
 * Entidad Acudiente
 * Representa al acudiente (padre, madre, tutor) de un estudiante
 */
class Acudiente
{
    private ?int $id_acudiente = null;
    private string $nombre;
    private string $apellido;
    private string $tipo_documento; // CC, CE, Pasaporte
    private string $numero_documento;
    private string $telefono;
    private ?string $telefono_secundario = null;
    private ?string $email = null;
    private ?string $direccion = null;
    private ?string $ocupacion = null;

    // Getters
    public function getIdAcudiente(): ?int
    {
        return $this->id_acudiente;
    }

    public function getNombre(): string
    {
        return $this->nombre;
    }

    public function getApellido(): string
    {
        return $this->apellido;
    }

    public function getNombreCompleto(): string
    {
        return $this->nombre . ' ' . $this->apellido;
    }

    public function getTipoDocumento(): string
    {
        return $this->tipo_documento;
    }

    public function getNumeroDocumento(): string
    {
        return $this->numero_documento;
    }

    public function getTelefono(): string
    {
        return $this->telefono;
    }

    public function getTelefonoSecundario(): ?string
    {
        return $this->telefono_secundario;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function getOcupacion(): ?string
    {
        return $this->ocupacion;
    }

    // Setters
    public function setIdAcudiente(?int $id): self
    {
        $this->id_acudiente = $id;
        return $this;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = trim($nombre);
        return $this;
    }

    public function setApellido(string $apellido): self
    {
        $this->apellido = trim($apellido);
        return $this;
    }

    public function setTipoDocumento(string $tipo_documento): self
    {
        if (!in_array($tipo_documento, ['CC', 'CE', 'Pasaporte'])) {
            throw new \InvalidArgumentException("Tipo de documento inválido: $tipo_documento");
        }
        $this->tipo_documento = $tipo_documento;
        return $this;
    }

    public function setNumeroDocumento(string $numero_documento): self
    {
        $this->numero_documento = trim($numero_documento);
        return $this;
    }

    public function setTelefono(string $telefono): self
    {
        $this->telefono = trim($telefono);
        return $this;
    }

    public function setTelefonoSecundario(?string $telefono_secundario): self
    {
        $this->telefono_secundario = $telefono_secundario ? trim($telefono_secundario) : null;
        return $this;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email ? trim($email) : null;
        return $this;
    }

    public function setDireccion(?string $direccion): self
    {
        $this->direccion = $direccion ? trim($direccion) : null;
        return $this;
    }

    public function setOcupacion(?string $ocupacion): self
    {
        $this->ocupacion = $ocupacion ? trim($ocupacion) : null;
        return $this;
    }

    /**
     * Validar datos del acudiente
     */
    public function validar(): array
    {
        $errores = [];

        if (empty($this->nombre)) {
            $errores[] = "El nombre del acudiente es obligatorio";
        }

        if (empty($this->apellido)) {
            $errores[] = "El apellido del acudiente es obligatorio";
        }

        if (empty($this->numero_documento)) {
            $errores[] = "El número de documento del acudiente es obligatorio";
        }

        if (empty($this->telefono)) {
            $errores[] = "El teléfono del acudiente es obligatorio";
        }

        if ($this->email && !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errores[] = "El email del acudiente no es válido";
        }

        return $errores;
    }

    /**
     * Convertir a array
     */
    public function toArray(): array
    {
        return [
            'id_acudiente' => $this->id_acudiente,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'nombre_completo' => $this->getNombreCompleto(),
            'tipo_documento' => $this->tipo_documento,
            'numero_documento' => $this->numero_documento,
            'telefono' => $this->telefono,
            'telefono_secundario' => $this->telefono_secundario,
            'email' => $this->email,
            'direccion' => $this->direccion,
            'ocupacion' => $this->ocupacion
        ];
    }

    /**
     * Crear desde array
     */
    public static function fromArray(array $data): self
    {
        $acudiente = new self();
        
        if (isset($data['id_acudiente'])) {
            $acudiente->setIdAcudiente($data['id_acudiente']);
        }
        
        $acudiente->setNombre($data['nombre'] ?? '')
                  ->setApellido($data['apellido'] ?? '')
                  ->setTipoDocumento($data['tipo_documento'] ?? 'CC')
                  ->setNumeroDocumento($data['numero_documento'] ?? '')
                  ->setTelefono($data['telefono'] ?? '')
                  ->setTelefonoSecundario($data['telefono_secundario'] ?? null)
                  ->setEmail($data['email'] ?? null)
                  ->setDireccion($data['direccion'] ?? null)
                  ->setOcupacion($data['ocupacion'] ?? null);
        
        return $acudiente;
    }
}
