<?php

namespace App\Core;

/**
 * Clase Validator - Validación de datos
 */
class Validator
{
    private array $errors = [];

    /**
     * Validar campo requerido
     */
    public function required(string $field, $value, string $message = null): self
    {
        if (empty($value)) {
            $this->errors[$field][] = $message ?? "El campo $field es obligatorio";
        }
        return $this;
    }

    /**
     * Validar email
     */
    public function email(string $field, $value, string $message = null): self
    {
        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = $message ?? "El campo $field debe ser un email válido";
        }
        return $this;
    }

    /**
     * Validar longitud mínima
     */
    public function min(string $field, $value, int $min, string $message = null): self
    {
        if (!empty($value) && strlen($value) < $min) {
            $this->errors[$field][] = $message ?? "El campo $field debe tener al menos $min caracteres";
        }
        return $this;
    }

    /**
     * Validar longitud máxima
     */
    public function max(string $field, $value, int $max, string $message = null): self
    {
        if (!empty($value) && strlen($value) > $max) {
            $this->errors[$field][] = $message ?? "El campo $field no puede exceder $max caracteres";
        }
        return $this;
    }

    /**
     * Validar rango numérico
     */
    public function between(string $field, $value, float $min, float $max, string $message = null): self
    {
        $numValue = floatval($value);
        if ($numValue < $min || $numValue > $max) {
            $this->errors[$field][] = $message ?? "El campo $field debe estar entre $min y $max";
        }
        return $this;
    }

    /**
     * Validar que sea numérico
     */
    public function numeric(string $field, $value, string $message = null): self
    {
        if (!empty($value) && !is_numeric($value)) {
            $this->errors[$field][] = $message ?? "El campo $field debe ser numérico";
        }
        return $this;
    }

    /**
     * Validar fecha
     */
    public function date(string $field, $value, string $message = null): self
    {
        if (!empty($value)) {
            $d = \DateTime::createFromFormat('Y-m-d', $value);
            if (!$d || $d->format('Y-m-d') !== $value) {
                $this->errors[$field][] = $message ?? "El campo $field debe ser una fecha válida (YYYY-MM-DD)";
            }
        }
        return $this;
    }

    /**
     * Verificar si hay errores
     */
    public function fails(): bool
    {
        return !empty($this->errors);
    }

    /**
     * Obtener todos los errores
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Obtener errores de un campo específico
     */
    public function getErrors(string $field): array
    {
        return $this->errors[$field] ?? [];
    }
}
