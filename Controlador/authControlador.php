<?php
class AuthControlador {
    private $usuario;
    private $cliente;
    private $oportunidad;
    private $actividad;

    public function __construct() {
        $this->usuario = new Usuario();
        $this->cliente = new Cliente();
        $this->oportunidad = new Oportunidad();
        $this->actividad = new Actividad();
    }

    public function requiereLogin() {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?ruta=login'); exit;
        }
    }

    public function requiereAdmin() {
        $this->requiereLogin();
        if ($_SESSION['rol'] != 'admin') {
            header('Location: index.php?ruta=dashboard'); exit;
        }
    }

    public function login() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $this->usuario->autenticar(trim($_POST['usuario']), $_POST['password']);
            if ($user) {
                session_regenerate_id(true);
                $_SESSION['id_usuario'] = $user['id_usuario'];
                $_SESSION['rol'] = $user['rol'];
                header('Location: index.php?ruta=dashboard'); exit;
            }
            $error = 'Usuario o contraseña incorrectos.';
        }
        require __DIR__ . '/../Vista/login.php';
    }

    public function registro() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $datos = ['usuario'=>trim($_POST['usuario']), 'nombre'=>trim($_POST['nombre']),
                'apellidos'=>trim($_POST['apellidos']), 'email'=>trim($_POST['email']),
                'password'=>$_POST['password'], 'rol'=>'vendedor'];
            if (in_array('', [$datos['usuario'], $datos['nombre'], $datos['apellidos'], $datos['email'], $datos['password']], true)) {
                $error = 'Todos los campos son obligatorios.';
            } elseif (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
                $error = 'El email no es válido.';
            } elseif (!$this->usuario->crear($datos)) {
                $error = 'El usuario o email ya están registrados.';
            } else {
                header('Location: index.php?ruta=login'); exit;
            }
        }
        require __DIR__ . '/../Vista/registro.php';
    }

    public function logout() {
        $_SESSION = [];
        session_destroy();
        header('Location: index.php?ruta=login'); exit;
    }

    public function dashboard() {
        $this->requiereLogin();
        $totalClientes = $this->cliente->total();
        $oportunidades = $this->oportunidad->resumen();
        $actividadesPendientes = $this->actividad->pendientes();
        $usuarios = $_SESSION['rol'] == 'admin' ? $this->usuario->todos() : [];
        require __DIR__ . '/../Vista/dashboard.php';
    }

    public function usuarios() {
        $this->requiereAdmin();
        header('Location: index.php?ruta=dashboard');
        exit;
    }

    public function guardarUsuario() {
        $this->requiereAdmin();
        $datos = ['id_usuario'=>(int)($_POST['id_usuario'] ?? 0), 'usuario'=>trim($_POST['usuario']), 'nombre'=>trim($_POST['nombre']),
            'apellidos'=>trim($_POST['apellidos']), 'email'=>trim($_POST['email']),
            'password'=>$_POST['password'], 'rol'=>$_POST['rol']];
        if ($datos['usuario'] === '' || $datos['nombre'] === '' || $datos['apellidos'] === '' || $datos['email'] === '') {
            $error = 'Los datos del usuario son obligatorios.';
        } elseif (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $error = 'El email no es válido.';
        } elseif (!in_array($datos['rol'], ['admin', 'vendedor'], true)) {
            $error = 'El rol no es válido.';
        } elseif ($datos['id_usuario'] > 0 && $this->usuario->actualizar($datos)) {
            header('Location: index.php?ruta=usuarios'); exit;
        } elseif ($datos['id_usuario'] === 0 && $datos['password'] !== '' && $this->usuario->crear($datos)) {
            header('Location: index.php?ruta=usuarios'); exit;
        } else {
            $error = 'No se ha podido crear el usuario.';
        }
        $usuarios = $this->usuario->todos();
        $totalClientes = $this->cliente->total();
        $oportunidades = $this->oportunidad->resumen();
        $actividadesPendientes = $this->actividad->pendientes();
        require __DIR__ . '/../Vista/dashboard.php';
    }

    public function eliminarUsuario() {
        $this->requiereAdmin();
        if ((int)$_GET['id'] !== (int)$_SESSION['id_usuario']) {
            $this->usuario->eliminar((int)$_GET['id'], $_SESSION['id_usuario']);
        }
        header('Location: index.php?ruta=usuarios'); exit;
    }
}
