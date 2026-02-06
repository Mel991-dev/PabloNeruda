<?php

namespace App\Core;

/**
 * Clase Router - Enrutamiento simple de la aplicación
 */
class Router
{
    private array $routes = [];
    private Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Registrar ruta GET
     */
    public function get(string $uri, $handler, array $middleware = []): void
    {
        $this->addRoute('GET', $uri, $handler, $middleware);
    }

    /**
     * Registrar ruta POST
     */
    public function post(string $uri, $handler, array $middleware = []): void
    {
        $this->addRoute('POST', $uri, $handler, $middleware);
    }

    /**
     * Agregar ruta al registro
     */
    private function addRoute(string $method, string $uri, $handler, array $middleware): void
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'handler' => $handler,
            'middleware' => $middleware
        ];
    }

    /**
     * Resolver y ejecutar la ruta
     */
    public function resolve(): void
    {
        $requestMethod = $this->request->method();
        $requestUri = $this->request->uri();

        foreach ($this->routes as $route) {
            if ($this->match($route, $requestMethod, $requestUri)) {
                $this->executeRoute($route);
                return;
            }
        }

        // Ruta no encontrada
        Response::error(404, "La página solicitada no existe.");
    }

    /**
     * Verificar si una ruta coincide
     */
    private function match(array $route, string $method, string $uri): bool
    {
        if ($route['method'] !== $method) {
            return false;
        }

        // Convertir parámetros dinámicos {id} a regex
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $route['uri']);
        $pattern = '#^' . $pattern . '$#';

        return preg_match($pattern, $uri);
    }

    /**
     * Ejecutar la ruta
     */
    private function executeRoute(array $route): void
    {
        // Ejecutar middleware
        foreach ($route['middleware'] as $middleware) {
            $this->executeMiddleware($middleware);
        }

        // Ejecutar handler
        if (is_callable($route['handler'])) {
            call_user_func($route['handler']);
        } elseif (is_string($route['handler'])) {
            $this->executeController($route['handler']);
        }
    }

    /**
     * Ejecutar middleware
     */
    private function executeMiddleware(string $middleware): void
    {
        // Formato: "auth" o "role:Administrador,Coordinador"
        $parts = explode(':', $middleware);
        $middlewareName = $parts[0];
        $params = isset($parts[1]) ? explode(',', $parts[1]) : [];

        $middlewareClass = "App\\Application\\Middleware\\" . ucfirst($middlewareName) . "Middleware";

        if (class_exists($middlewareClass)) {
            $middlewareInstance = new $middlewareClass();
            $middlewareInstance->handle($this->request, $params);
        }
    }

    /**
     * Ejecutar controlador
     */
    private function executeController(string $handler): void
    {
        // Formato: "ControllerName@methodName"
        list($controllerName, $method) = explode('@', $handler);

        $controllerClass = "App\\Application\\Controllers\\$controllerName";

        if (!class_exists($controllerClass)) {
            Response::error(500, "Controlador no encontrado: $controllerName");
            return;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            Response::error(500, "Método no encontrado: $method en $controllerName");
            return;
        }

        // Extraer parámetros de la URI si existen
        $params = $this->extractParams($handler);
        call_user_func_array([$controller, $method], $params);
    }

    /**
     * Extraer parámetros de la URI
     */
    private function extractParams(string $handler): array
    {
        // Por ahora retornamos array vacío, se puede extender para parámetros dinámicos
        return [];
    }
}
