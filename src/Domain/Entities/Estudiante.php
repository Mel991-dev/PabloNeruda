<?php

namespace App\Domain\Entities;

/**
 * Entidad Estudiante
 * Representa a un estudiante de la escuela
 */
class Estudiante
{
    private ?int $id_estudiante = null;
    private string $nombre;
    private string $apellido;
    private string $fecha_nacimiento;
    private string $tipo_documento; // RC, TI
    private string $numero_documento;
    private ?string $registro_civil = null;
    private ?string $tarjeta_identidad = null;
    private ?string $documento_pdf = null;
    private bool $tiene_alergias = false;
    private ?string $descripcion_alergias = null;
    private int $numero_hermanos = 0;
    private string $estado = 'Activo'; // Activo, Retirado, Graduado
    private ?string $fecha_registro = null;
    private ?string $nombre_curso = null;
    
    // Propiedades V2 (Relaciones)
    private ?string $lugar_nacimiento = null;
    private ?string $procedencia_institucion = null;
    
    private ?int $fk_padre = null;
    private ?int $fk_madre = null;
    
    // Objetos Relacionados
    private ?InfoSalud $info_salud = null;
    private ?InfoSocioeconomica $info_socioeconomica = null;
    private array $antecedentes_academicos = [];
    private ?Familiar $padre = null;
    private ?Familiar $madre = null;

    // Getters
    public function getIdEstudiante(): ?int
    {
        return $this->id_estudiante;
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

    public function getFechaNacimiento(): string
    {
        return $this->fecha_nacimiento;
    }

    public function getTipoDocumento(): string
    {
        return $this->tipo_documento;
    }

    public function getNumeroDocumento(): string
    {
        return $this->numero_documento;
    }

    public function getRegistroCivil(): ?string
    {
        return $this->registro_civil;
    }

    public function getTarjetaIdentidad(): ?string
    {
        return $this->tarjeta_identidad;
    }

    public function getDocumentoPdf(): ?string
    {
        return $this->documento_pdf;
    }

    public function tieneAlergias(): bool
    {
        return $this->tiene_alergias;
    }

    public function getDescripcionAlergias(): ?string
    {
        return $this->descripcion_alergias;
    }

    public function getNumeroHermanos(): int
    {
        return $this->numero_hermanos;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function getFechaRegistro(): ?string
    {
        return $this->fecha_registro;
    }

    public function getNombreCurso(): ?string
    {
        return $this->nombre_curso;
    }

    // Getters V2
    public function getLugarNacimiento(): ?string { return $this->lugar_nacimiento; }
    public function getProcedenciaInstitucion(): ?string { return $this->procedencia_institucion; }
    public function getInfoSalud(): ?InfoSalud { return $this->info_salud; }
    public function getInfoSocioeconomica(): ?InfoSocioeconomica { return $this->info_socioeconomica; }
    public function getAntecedentesAcademicos(): array { return $this->antecedentes_academicos; }
    public function getPadre(): ?Familiar { return $this->padre; }
    public function getMadre(): ?Familiar { return $this->madre; }

    public function getFkPadre(): ?int { return $this->fk_padre; }
    public function getFkMadre(): ?int { return $this->fk_madre; }

    // Setters V2
    public function setLugarNacimiento(?string $val): self { $this->lugar_nacimiento = $val; return $this; }
    public function setProcedenciaInstitucion(?string $val): self { $this->procedencia_institucion = $val; return $this; }
    public function setInfoSalud(?InfoSalud $val): self { $this->info_salud = $val; return $this; }
    public function setInfoSocioeconomica(?InfoSocioeconomica $val): self { $this->info_socioeconomica = $val; return $this; }
    public function setAntecedentesAcademicos(array $val): self { $this->antecedentes_academicos = $val; return $this; }
    public function setPadre(?Familiar $val): self { $this->padre = $val; return $this; }
    public function setMadre(?Familiar $val): self { $this->madre = $val; return $this; }
    public function setFkPadre(?int $id): self { $this->fk_padre = $id; return $this; }
    public function setFkMadre(?int $id): self { $this->fk_madre = $id; return $this; }


    // Setters
    public function setIdEstudiante(?int $id): self
    {
        $this->id_estudiante = $id;
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

    public function setFechaNacimiento(string $fecha_nacimiento): self
    {
        $this->fecha_nacimiento = $fecha_nacimiento;
        return $this;
    }

    public function setTipoDocumento(string $tipo_documento): self
    {
        if (!in_array($tipo_documento, ['RC', 'TI'])) {
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

    public function setRegistroCivil(?string $registro_civil): self
    {
        $this->registro_civil = $registro_civil ? trim($registro_civil) : null;
        return $this;
    }

    public function setTarjetaIdentidad(?string $tarjeta_identidad): self
    {
        $this->tarjeta_identidad = $tarjeta_identidad ? trim($tarjeta_identidad) : null;
        return $this;
    }

    public function setDocumentoPdf(?string $documento_pdf): self
    {
        $this->documento_pdf = $documento_pdf;
        return $this;
    }

    public function setTieneAlergias(bool $tiene_alergias): self
    {
        $this->tiene_alergias = $tiene_alergias;
        return $this;
    }

    public function setDescripcionAlergias(?string $descripcion_alergias): self
    {
        $this->descripcion_alergias = $descripcion_alergias ? trim($descripcion_alergias) : null;
        return $this;
    }

    public function setNumeroHermanos(int $numero_hermanos): self
    {
        $this->numero_hermanos = max(0, $numero_hermanos);
        return $this;
    }

    public function setEstado(string $estado): self
    {
        if (!in_array($estado, ['Activo', 'Retirado', 'Graduado'])) {
            throw new \InvalidArgumentException("Estado inválido: $estado");
        }
        $this->estado = $estado;
        return $this;
    }

    public function setFechaRegistro(?string $fecha_registro): self
    {
        $this->fecha_registro = $fecha_registro;
        return $this;
    }

    public function setNombreCurso(?string $nombre_curso): self
    {
        $this->nombre_curso = $nombre_curso;
        return $this;
    }

    /**
     * Calcular edad del estudiante
     */
    public function calcularEdad(): int
    {
        $fechaNac = new \DateTime($this->fecha_nacimiento);
        $hoy = new \DateTime();
        return $hoy->diff($fechaNac)->y;
    }

    /**
     * Validar datos del estudiante
     */
    public function validar(): array
    {
        $errores = [];

        if (empty($this->nombre)) {
            $errores[] = "El nombre es obligatorio";
        }

        if (empty($this->apellido)) {
            $errores[] = "El apellido es obligatorio";
        }

        if (empty($this->fecha_nacimiento)) {
            $errores[] = "La fecha de nacimiento es obligatoria";
        } else {
            $edad = $this->calcularEdad();
            if ($edad < 3 || $edad > 15) {
                $errores[] = "La edad debe estar entre 3 y 15 años";
            }
        }

        if (empty($this->numero_documento)) {
            $errores[] = "El número de documento es obligatorio";
        }

        if ($this->tiene_alergias && empty($this->descripcion_alergias)) {
            $errores[] = "Si el estudiante tiene alergias, debe especificar cuáles";
        }

        return $errores;
    }

    /**
     * Convertir a array
     */
    public function toArray(): array
    {
        return [
            'id_estudiante' => $this->id_estudiante,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'nombre_completo' => $this->getNombreCompleto(),
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'edad' => $this->calcularEdad(),
            'tipo_documento' => $this->tipo_documento,
            'numero_documento' => $this->numero_documento,
            'registro_civil' => $this->registro_civil,
            'tarjeta_identidad' => $this->tarjeta_identidad,
            'documento_pdf' => $this->documento_pdf,
            'tiene_alergias' => $this->tiene_alergias,
            'descripcion_alergias' => $this->descripcion_alergias,
            'numero_hermanos' => $this->numero_hermanos,
            'estado' => $this->estado,
            'fecha_registro' => $this->fecha_registro,
            'lugar_nacimiento' => $this->lugar_nacimiento,
            'procedencia_institucion' => $this->procedencia_institucion,
            'fk_padre' => $this->fk_padre,
            'fk_madre' => $this->fk_madre
        ];
    }

    /**
     * Crear desde array
     */
    public static function fromArray(array $data): self
    {
        $estudiante = new self();
        
        if (isset($data['id_estudiante'])) {
            $estudiante->setIdEstudiante($data['id_estudiante']);
        }
        
        $estudiante->setNombre($data['nombre'] ?? '')
                   ->setApellido($data['apellido'] ?? '')
                   ->setFechaNacimiento($data['fecha_nacimiento'] ?? '')
                   ->setTipoDocumento($data['tipo_documento'] ?? 'RC')
                   ->setNumeroDocumento($data['numero_documento'] ?? '')
                   ->setRegistroCivil($data['registro_civil'] ?? null)
                   ->setTarjetaIdentidad($data['tarjeta_identidad'] ?? null)
                   ->setDocumentoPdf($data['documento_pdf'] ?? null)
                   ->setTieneAlergias((bool)($data['tiene_alergias'] ?? false))
                   ->setDescripcionAlergias($data['descripcion_alergias'] ?? null)
                   ->setNumeroHermanos((int)($data['numero_hermanos'] ?? 0))
                   ->setEstado($data['estado'] ?? 'Activo');
        
        if (isset($data['fecha_registro'])) {
            $estudiante->setFechaRegistro($data['fecha_registro']);
        }
        
        // Mapeo V2
        if (isset($data['lugar_nacimiento'])) $estudiante->setLugarNacimiento($data['lugar_nacimiento']);
        if (isset($data['procedencia_institucion'])) $estudiante->setProcedenciaInstitucion($data['procedencia_institucion']);
        if (isset($data['fk_padre'])) $estudiante->setFkPadre((int)$data['fk_padre']);
        if (isset($data['fk_madre'])) $estudiante->setFkMadre((int)$data['fk_madre']);

        
        return $estudiante;
    }
}
