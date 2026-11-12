<?php
class AuthControlador {
    public function login() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = Usuario::autenticar(trim($_POST['usuario']), $_POST['password']);
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $datos = ['usuario'=>trim($_POST['usuario']), 'nombre'=>trim($_POST['nombre']),
                'apellidos'=>trim($_POST['apellidos']), 'email'=>trim($_POST['email']),
                'password'=>$_POST['password'], 'rol'=>'vendedor'];
            if (in_array('', [$datos['usuario'], $datos['nombre'], $datos['apellidos'], $datos['email'], $datos['password']], true)) {
                $error = 'Todos los campos son obligatorios.';
            } elseif (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
                $error = 'El email no es válido.';
            } elseif (!Usuario::crear($datos)) {
                $error = 'El usuario o email ya están registrados.';
            } else {
                header('Location: index.php?ruta=login'); exit;
            }
        }
        require __DIR__ . '/../Vista/registro.php';
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?ruta=login'); exit;
    }

    public function dashboard() {
        self::requiereLogin();
        $totalClientes = Cliente::total();
        $oportunidades = Oportunidad::resumen();
        $actividadesPendientes = Actividad::pendientes();
        $usuarios = $_SESSION['rol'] == 'admin' ? Usuario::todos() : [];
        require __DIR__ . '/../Vista/dashboard.php';
    }

    public function usuarios() {
        self::requiereLogin();
        if ($_SESSION['rol'] != 'admin') {
            header('Location: index.php?ruta=dashboard'); exit;
        }
        header('Location: index.php?ruta=dashboard'); exit;
    }

    public function guardarUsuario() {
        self::requiereLogin();
        if ($_SESSION['rol'] != 'admin') {
            header('Location: index.php?ruta=dashboard'); exit;
        }
        $datos = ['id_usuario'=>(int)($_POST['id_usuario'] ?? 0), 'usuario'=>trim($_POST['usuario']), 'nombre'=>trim($_POST['nombre']),
            'apellidos'=>trim($_POST['apellidos']), 'email'=>trim($_POST['email']),
            'password'=>$_POST['password'], 'rol'=>$_POST['rol']];
        if ($datos['usuario'] === '' || $datos['nombre'] === '' || $datos['apellidos'] === '' || $datos['email'] === '') {
            $error = 'Los datos del usuario son obligatorios.';
        } elseif (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $error = 'El email no es válido.';
        } elseif (!in_array($datos['rol'], ['admin', 'vendedor'], true)) {
            $error = 'El rol no es válido.';
        } elseif ($datos['id_usuario'] > 0 && Usuario::actualizar($datos)) {
            header('Location: index.php?ruta=usuarios'); exit;
        } elseif ($datos['id_usuario'] === 0 && $datos['password'] !== '' && Usuario::crear($datos)) {
            header('Location: index.php?ruta=usuarios'); exit;
        } else {
            $error = 'No se ha podido crear el usuario.';
        }
        $usuarios = Usuario::todos();
        $totalClientes = Cliente::total();
        $oportunidades = Oportunidad::resumen();
        $actividadesPendientes = Actividad::pendientes();
        require __DIR__ . '/../Vista/dashboard.php';
    }

    public function eliminarUsuario() {
        self::requiereLogin();
        if ($_SESSION['rol'] != 'admin') {
            header('Location: index.php?ruta=dashboard'); exit;
        }
        if ((int)$_GET['id'] !== (int)$_SESSION['id_usuario']) {
            Usuario::eliminar((int)$_GET['id'], $_SESSION['id_usuario']);
        }
        header('Location: index.php?ruta=usuarios'); exit;
    }

    public static function requiereLogin() {
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: index.php?ruta=login'); exit;
        }
    }
}
