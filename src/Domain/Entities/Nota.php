<?php

namespace App\Domain\Entities;

class Nota
{
    private ?int $id_nota = null;
    private int $fk_matricula;
    private int $fk_materia;
    private int $fk_profesor; // Nuevo campo
    private int $periodo; // 1, 2, 3, 4
    private float $nota1 = 0.0;
    private float $nota2 = 0.0;
    private float $nota3 = 0.0;
    private float $nota4 = 0.0;
    private float $nota5 = 0.0;
    private float $promedio = 0.0;
    private string $estado; // Aprobado, Reprobado
    private ?string $observaciones = null;
    private ?string $fecha_registro = null;

    // Getters
    public function getIdNota(): ?int { return $this->id_nota; }
    public function getFkMatricula(): int { return $this->fk_matricula; }
    public function getFkMateria(): int { return $this->fk_materia; }
    public function getFkProfesor(): int { return $this->fk_profesor; }
    public function getPeriodo(): int { return $this->periodo; }
    public function getNota1(): float { return $this->nota1; }
    public function getNota2(): float { return $this->nota2; }
    public function getNota3(): float { return $this->nota3; }
    public function getNota4(): float { return $this->nota4; }
    public function getNota5(): float { return $this->nota5; }
    public function getPromedio(): float { return $this->promedio; }
    public function getEstado(): string { return $this->estado; }
    public function getObservaciones(): ?string { return $this->observaciones; }

    // Setters
    public function setIdNota(?int $id): self { $this->id_nota = $id; return $this; }
    public function setFkMatricula(int $id): self { $this->fk_matricula = $id; return $this; }
    public function setFkMateria(int $id): self { $this->fk_materia = $id; return $this; }
    public function setFkProfesor(int $id): self { $this->fk_profesor = $id; return $this; }
    
    public function setPeriodo(int $periodo): self 
    {
        if ($periodo < 1 || $periodo > 4) {
            throw new \InvalidArgumentException("El periodo debe estar entre 1 y 4");
        }
        $this->periodo = $periodo;
        return $this;
    }

    public function setNotas(float $n1, float $n2, float $n3, float $n4, float $n5): self
    {
        $this->validarNota($n1); $this->nota1 = $n1;
        $this->validarNota($n2); $this->nota2 = $n2;
        $this->validarNota($n3); $this->nota3 = $n3;
        $this->validarNota($n4); $this->nota4 = $n4;
        $this->validarNota($n5); $this->nota5 = $n5;
        
        $this->calcularPromedio();
        return $this;
    }

    public function setObservaciones(?string $obs): self { $this->observaciones = $obs; return $this; }

    private function validarNota(float $nota): void
    {
        if ($nota < 0.0 || $nota > 5.0) {
            throw new \InvalidArgumentException("Las notas deben estar entre 0.0 y 5.0. Valor inválido: $nota");
        }
    }

    private function calcularPromedio(): void
    {
        // Promedio simple de 5 notas
        $suma = $this->nota1 + $this->nota2 + $this->nota3 + $this->nota4 + $this->nota5;
        $this->promedio = round($suma / 5, 2); // Redondear a 2 decimales
        
        // Determinar estado
        $this->estado = ($this->promedio >= 3.0) ? 'Aprobado' : 'Reprobado';
    }

    public static function fromArray(array $data): self
    {
        $nota = new self();
        if (isset($data['id_nota'])) $nota->setIdNota($data['id_nota']);
        
        $nota->setFkMatricula($data['fk_matricula'])
             ->setFkMateria($data['fk_materia'])
             ->setPeriodo($data['periodo'])
             ->setNotas(
                 (float)($data['nota_1'] ?? $data['nota1'] ?? 0),
                 (float)($data['nota_2'] ?? $data['nota2'] ?? 0),
                 (float)($data['nota_3'] ?? $data['nota3'] ?? 0),
                 (float)($data['nota_4'] ?? $data['nota4'] ?? 0),
                 (float)($data['nota_5'] ?? $data['nota5'] ?? 0)
             );
             
        if (isset($data['observaciones'])) $nota->setObservaciones($data['observaciones']);
        
        // Estos valores se calculan, pero si vienen de BD, los asignamos (opcionalmente)
        // El setter de notas ya calcula promedio y estado, así que no es estrictamente necesario setearlos si confiamos en el cálculo
        
        return $nota;
    }

    public function toArray(): array
    {
        return [
            'id_nota' => $this->id_nota,
            'fk_matricula' => $this->fk_matricula,
            'fk_materia' => $this->fk_materia,
            'periodo' => $this->periodo,
            'nota1' => $this->nota1,
            'nota2' => $this->nota2,
            'nota3' => $this->nota3,
            'nota4' => $this->nota4,
            'nota5' => $this->nota5,
            'promedio' => $this->promedio,
            'estado' => $this->estado,
            'observaciones' => $this->observaciones
        ];
    }
}
