<?php

namespace App\Core;

/**
 * Clase Request - Manejo de peticiones HTTP
 */
class Request
{
    private array $query;
    private array $request;
    private array $server;
    private array $files;
    private string $method;
    private string $uri;

    public function __construct()
    {
        $this->query = $_GET;
        $this->request = $_POST;
        $this->server = $_SERVER;
        $this->files = $_FILES;
        $this->method = $this->server['REQUEST_METHOD'] ?? 'GET';
        $this->uri = $this->parseUri();
    }

    /**
     * Obtener la URI limpia
     */
    private function parseUri(): string
    {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        
        // Remover query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        // Remover el prefijo /pablo_neruda si existe
        $uri = preg_replace('#^/pablo_neruda#', '', $uri);
        
        // Remover /public/ si existe en la URI
        $uri = str_replace('/public', '', $uri);
        
        return rtrim($uri, '/') ?: '/';
    }

    /**
     * Obtener método HTTP
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * Obtener URI
     */
    public function uri(): string
    {
        return $this->uri;
    }

    /**
     * Obtener parámetro GET
     */
    public function query(string $key, $default = null)
    {
        return $this->query[$key] ?? $default;
    }

    /**
     * Obtener parámetro POST
     */
    public function input(string $key, $default = null)
    {
        return $this->request[$key] ?? $default;
    }

    /**
     * Obtener todos los inputs POST
     */
    public function all(): array
    {
        return $this->request;
    }

    /**
     * Obtener archivo subido
     */
    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    /**
     * Verificar si es una petición POST
     */
    public function isPost(): bool
    {
        return $this->method === 'POST';
    }

    /**
     * Verificar si es una petición GET
     */
    public function isGet(): bool
    {
        return $this->method === 'GET';
    }

    /**
     * Verificar si es una petición AJAX
     */
    public function isAjax(): bool
    {
        return !empty($this->server['HTTP_X_REQUESTED_WITH']) && 
               strtolower($this->server['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Obtener IP del cliente
     */
    public function ip(): string
    {
        return $this->server['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Sanitizar input
     */
    public function sanitize(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}
