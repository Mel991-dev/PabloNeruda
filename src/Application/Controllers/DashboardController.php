<?php

namespace App\Application\Controllers;

use App\Core\{Request, Response, Session};
use App\Domain\Services\AuthService;
use App\Domain\Services\DashboardService;
use App\Domain\Services\AuditService;
use App\Infrastructure\Repositories\MySQLUsuarioRepository;

/**
 * Controlador del Dashboard
 */
class DashboardController
{
    private AuthService $authService;

    public function __construct()
    {
        $usuarioRepository = new MySQLUsuarioRepository();
        $this->authService = new AuthService($usuarioRepository);
    }

    /**
     * Mostrar dashboard según el rol del usuario
     */
    public function index(): void
    {
        $usuario = $this->authService->getCurrentUser();

        if (!$usuario) {
            Response::redirect(APP_URL . '/login');
            return;
        }

        $rol = $usuario->getRol();
        $data = [
            'usuario' => $usuario,
            'rol' => $rol
        ];

        switch ($rol) {
            case 'Administrador':
                $dashboardService = new DashboardService();
                $auditService = new AuditService();
                $data['stats'] = $dashboardService->obtenerEstadisticasAdmin();
                
                // Obtener filtros para la auditoría (si los hay)
                $request = new Request();
                $filtros = [
                    'accion' => $request->query('accion'),
                    'rol' => $request->query('rol'),
                    'orden' => $request->query('orden')
                ];
                
                $data['logs'] = $auditService->obtenerActividadReciente($filtros, 10);
                $data['filtros_actuales'] = $filtros;

                Response::view('dashboard.administrador', $data);
                break;
            case 'Rector':
                $dashboardService = new DashboardService();
                $data['stats'] = $dashboardService->obtenerEstadisticasRector();
                Response::view('dashboard.rector', $data);
                break;
            case 'Coordinador':
                $dashboardService = new DashboardService();
                $data['stats'] = $dashboardService->obtenerEstadisticasCoordinador();
                Response::view('dashboard.coordinador', $data);
                break;
            case 'Profesor':
                $dashboardService = new DashboardService();
                $fkProfesor = Session::get('fk_profesor');
                $data['stats'] = $dashboardService->obtenerEstadisticasProfesor($fkProfesor ?? 0);
                Response::view('dashboard.profesor', $data);
                break;
            case 'Orientador':
                $dashboardService = new DashboardService();
                $data['stats'] = $dashboardService->obtenerEstadisticasOrientador();
                Response::view('dashboard.orientador', $data);
                break;
            default: // Estudiante / Acudiente or other roles
                Response::view('dashboard.default', $data);
                break;
        }
    }
}
