<?php

namespace App\Domain\Entities;

/**
 * Entidad Seguimiento - Representa una intervención de orientación
 */
class Seguimiento
{
    private ?int $id_seguimiento;
    private int $fk_estudiante;
    private int $fk_usuario_orientador;
    private string $fecha;
    private string $tipo_intervencion;
    private string $motivo;
    private string $descripcion;
    private ?string $compromisos;
    private ?string $remitido_a;
    private string $estado;
    private ?string $fecha_cierre;

    public function __construct(
        int $fk_estudiante,
        int $fk_usuario_orientador,
        string $tipo_intervencion,
        string $motivo,
        string $descripcion,
        ?string $compromisos = null,
        ?string $remitido_a = null,
        string $estado = 'Abierto',
        ?int $id_seguimiento = null,
        ?string $fecha = null,
        ?string $fecha_cierre = null
    ) {
        $this->id_seguimiento = $id_seguimiento;
        $this->fk_estudiante = $fk_estudiante;
        $this->fk_usuario_orientador = $fk_usuario_orientador;
        $this->fecha = $fecha ?? date('Y-m-d H:i:s');
        $this->tipo_intervencion = $tipo_intervencion;
        $this->motivo = $motivo;
        $this->descripcion = $descripcion;
        $this->compromisos = $compromisos;
        $this->remitido_a = $remitido_a;
        $this->estado = $estado;
        $this->fecha_cierre = $fecha_cierre;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (int) $data['fk_estudiante'],
            (int) $data['fk_usuario_orientador'],
            $data['tipo_intervencion'],
            $data['motivo'],
            $data['descripcion'],
            $data['compromisos'] ?? null,
            $data['remitido_a'] ?? null,
            $data['estado'] ?? 'Abierto',
            isset($data['id_seguimiento']) ? (int) $data['id_seguimiento'] : null,
            $data['fecha_seguimiento'] ?? null,
            $data['fecha_cierre'] ?? null
        );
    }

    // Getters
    public function getId(): ?int { return $this->id_seguimiento; }
    public function getEstudianteId(): int { return $this->fk_estudiante; }
    public function getOrientadorId(): int { return $this->fk_usuario_orientador; }
    public function getFecha(): string { return $this->fecha; }
    public function getTipo(): string { return $this->tipo_intervencion; }
    public function getMotivo(): string { return $this->motivo; }
    public function getDescripcion(): string { return $this->descripcion; }
    public function getCompromisos(): ?string { return $this->compromisos; }
    public function getRemitidoA(): ?string { return $this->remitido_a; }
    public function getEstado(): string { return $this->estado; }
    public function getFechaCierre(): ?string { return $this->fecha_cierre; }
}
