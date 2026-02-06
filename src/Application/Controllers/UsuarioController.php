<?php

namespace App\Application\Controllers;

use App\Core\{Request, Response, Session};
use App\Domain\Services\AuthService;
use App\Infrastructure\Repositories\MySQLUsuarioRepository;
use App\Infrastructure\Repositories\MySQLProfesorRepository;
use App\Domain\Entities\Usuario;

class UsuarioController
{
    private MySQLUsuarioRepository $usuarioRepo;
    private MySQLProfesorRepository $profesorRepo;
    private AuthService $authService;

    public function __construct()
    {
        $this->usuarioRepo = new MySQLUsuarioRepository();
        $this->profesorRepo = new MySQLProfesorRepository();
        $this->authService = new AuthService($this->usuarioRepo);
    }

    /**
     * Listado de Usuarios
     */
    public function index(): void
    {
        $usuarios = $this->usuarioRepo->findAll();
        
        Response::view('usuarios.index', [
            'usuarios' => $usuarios,
            'titulo' => 'Gestión de Usuarios'
        ]);
    }

    /**
     * Formulario de Creación
     */
    public function create(): void
    {
        Response::view('usuarios.create', ['titulo' => 'Nuevo Usuario']);
    }

    /**
     * Guardar Usuario
     */
    public function store(): void
    {
        $request = new Request();
        
        if (!$request->isPost()) {
            Response::redirect(APP_URL . '/usuarios');
            return;
        }

        $data = [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
            'rol' => $request->input('rol'),
            'estado' => $request->input('estado', 'Activo')
        ];

        // Lógica para crear Profesor si el rol es 'Profesor'
        if ($data['rol'] === 'Profesor') {
            $profesorData = [
                'nombre' => $request->input('nombre'),
                'apellido' => $request->input('apellido'),
                'tipo_documento' => $request->input('tipo_documento'),
                'numero_documento' => $request->input('numero_documento'),
                'telefono' => $request->input('telefono'),
                'email' => $request->input('email'),
                'especialidad' => $request->input('especialidad')
            ];

            // Validar campos mínimos del profesor
            if (empty($profesorData['nombre']) || empty($profesorData['apellido']) || empty($profesorData['numero_documento'])) {
                Session::flash('error', 'Debe completar los datos del profesor');
                Response::redirect(APP_URL . '/usuarios/crear');
                return;
            }

            // Guardar profesor y obtener ID
            $profesorId = $this->profesorRepo->save($profesorData);
            $data['fk_profesor'] = $profesorId;
        }

        $result = $this->authService->createUser($data);

        if ($result['success']) {
            Session::flash('success', 'Usuario creado exitosamente');
            Response::redirect(APP_URL . '/usuarios');
        } else {
            Session::flash('error', $result['message']);
            Response::redirect(APP_URL . '/usuarios/crear');
        }
    }

    /**
     * Formulario de Edición
     */
    public function edit(): void
    {
        $request = new Request();
        $id = (int)$request->query('id');

        if (!$id) {
            Response::redirect(APP_URL . '/usuarios');
            return;
        }

        $usuario = $this->usuarioRepo->findById($id);

        if (!$usuario) {
            Session::flash('error', 'Usuario no encontrado');
            Response::redirect(APP_URL . '/usuarios');
            return;
        }

        $profesor = null;
        if ($usuario->getFkProfesor()) {
            $profesor = $this->profesorRepo->findById($usuario->getFkProfesor());
        }

        Response::view('usuarios.edit', [
            'usuario' => $usuario,
            'profesor' => $profesor,
            'titulo' => 'Editar Usuario'
        ]);
    }

    /**
     * Actualizar Usuario
     */
    public function update(): void
    {
        $request = new Request();
        
        if (!$request->isPost()) {
            Response::redirect(APP_URL . '/usuarios');
            return;
        }

        $id = (int)$request->input('id_usuario');
        $usuario = $this->usuarioRepo->findById($id);

        if (!$usuario) {
            Session::flash('error', 'Usuario no encontrado');
            Response::redirect(APP_URL . '/usuarios');
            return;
        }

        $usuario->setUsername($request->input('username'));
        $usuario->setRol($request->input('rol'));
        $usuario->setEstado($request->input('estado'));

        // Actualizar/Crear datos de profesor si aplica
        if ($usuario->getRol() === 'Profesor') {
            $profesorData = [
                'nombre' => $request->input('nombre'),
                'apellido' => $request->input('apellido'),
                'tipo_documento' => $request->input('tipo_documento'),
                'numero_documento' => $request->input('numero_documento'),
                'telefono' => $request->input('telefono'),
                'email' => $request->input('email'),
                'especialidad' => $request->input('especialidad')
            ];

            if ($usuario->getFkProfesor()) {
                 // Actualizar existente
                 $profesorData['id_profesor'] = $usuario->getFkProfesor();
                 $this->profesorRepo->update($profesorData);
            } else {
                 // Si era Profesor pero no tenía ficha (caso raro) o cambió de rol a Profesor recién
                 // Validar campos requeridos
                 if (!empty($profesorData['numero_documento'])) {
                     $profesorId = $this->profesorRepo->save($profesorData);
                     $usuario->setFkProfesor($profesorId);
                 }
            }
        }

        // Cambio de contraseña opcional
        $password = $request->input('password');
        if (!empty($password)) {
            $usuario->setPasswordHash($this->authService->hashPassword($password));
        }

        if ($this->usuarioRepo->update($usuario)) {
            Session::flash('success', 'Usuario actualizado correctamente');
            Response::redirect(APP_URL . '/usuarios');
        } else {
            Session::flash('error', 'Error al actualizar usuario');
            Response::redirect(APP_URL . "/usuarios/editar?id=$id");
        }
    }

    /**
     * Alternar Estado (Suspender/Activar)
     */
    public function toggleEstado(): void
    {
        $request = new Request();
        $id = (int)$request->query('id');

        // Evitar auto-suspensión
        if ($id === Session::get('user_id')) {
            Session::flash('error', 'No puedes suspender tu propia cuenta');
            Response::redirect(APP_URL . '/usuarios');
            return;
        }

        $usuario = $this->usuarioRepo->findById($id);

        if ($usuario) {
            $nuevoEstado = ($usuario->getEstado() === 'Activo') ? 'Inactivo' : 'Activo';
            $usuario->setEstado($nuevoEstado);
            $this->usuarioRepo->update($usuario);
            
            $msg = ($nuevoEstado === 'Inactivo') ? 'Usuario suspendido' : 'Usuario reactivado';
            Session::flash('success', $msg);
        }

        Response::redirect(APP_URL . '/usuarios');
    }
}
