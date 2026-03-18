<?php

namespace App\Domain\Entities;

class Notificacion
{
    private ?int $id;
    private ?int $usuarioOrigenId;
    private ?int $usuarioDestinoId;
    private ?string $rolDestino;
    private string $tipo;
    private string $titulo;
    private string $mensaje;
    private ?string $enlace;
    private bool $leida;
    private ?string $fechaCreacion;

    public function __construct(
        string $tipo,
        string $titulo,
        string $mensaje,
        ?string $rolDestino = null,
        ?int $usuarioOrigenId = null,
        ?int $usuarioDestinoId = null,
        ?string $enlace = null,
        bool $leida = false,
        ?int $id = null,
        ?string $fechaCreacion = null
    ) {
        $this->tipo = $tipo;
        $this->titulo = $titulo;
        $this->mensaje = $mensaje;
        $this->rolDestino = $rolDestino;
        $this->usuarioOrigenId = $usuarioOrigenId;
        $this->usuarioDestinoId = $usuarioDestinoId;
        $this->enlace = $enlace;
        $this->leida = $leida;
        $this->id = $id;
        $this->fechaCreacion = $fechaCreacion;
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getUsuarioOrigenId(): ?int { return $this->usuarioOrigenId; }
    public function getUsuarioDestinoId(): ?int { return $this->usuarioDestinoId; }
    public function getRolDestino(): ?string { return $this->rolDestino; }
    public function getTipo(): string { return $this->tipo; }
    public function getTitulo(): string { return $this->titulo; }
    public function getMensaje(): string { return $this->mensaje; }
    public function getEnlace(): ?string { return $this->enlace; }
    public function isLeida(): bool { return $this->leida; }
    public function getFechaCreacion(): ?string { return $this->fechaCreacion; }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['tipo'],
            $data['titulo'],
            $data['mensaje'],
            $data['rol_destino'] ?? null,
            $data['fk_usuario_origen'] ?? null,
            $data['fk_usuario_destino'] ?? null,
            $data['enlace'] ?? null,
            (bool)($data['leida'] ?? false),
            $data['id_notificacion'] ?? null,
            $data['fecha_creacion'] ?? null
        );
    }
}
