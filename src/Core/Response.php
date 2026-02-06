<?php

namespace App\Core;

/**
 * Clase Response - Manejo de respuestas HTTP
 */
class Response
{
    /**
     * Enviar respuesta JSON
     */
    public static function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Redirigir a una URL
     */
    public static function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    /**
     * Renderizar una vista
     */
    public static function view(string $view, array $data = []): void
    {
        extract($data);
        
        $viewPath = VIEWS_PATH . '/' . str_replace('.', '/', $view) . '.php';
        
        if (!file_exists($viewPath)) {
            self::error(404, "Vista no encontrada: $view");
            return;
        }
        
        require $viewPath;
    }

    /**
     * Mostrar página de error
     */
    public static function error(int $code, string $message = ''): void
    {
        http_response_code($code);
        
        $errorMessages = [
            403 => 'Acceso Prohibido',
            404 => 'Página No Encontrada',
            500 => 'Error Interno del Servidor'
        ];
        
        $title = $errorMessages[$code] ?? 'Error';
        
        echo "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>$title - " . APP_NAME . "</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
                h1 { font-size: 48px; color: #dc3545; }
                p { font-size: 18px; color: #666; }
                a { color: #007bff; text-decoration: none; }
            </style>
        </head>
        <body>
            <h1>$code</h1>
            <p><strong>$title</strong></p>
            " . ($message ? "<p>$message</p>" : "") . "
            <p><a href='" . APP_URL . "'>Volver al inicio</a></p>
        </body>
        </html>";
        exit;
    }

    /**
     * Establecer código de estado HTTP
     */
    public static function status(int $code): void
    {
        http_response_code($code);
    }
}
