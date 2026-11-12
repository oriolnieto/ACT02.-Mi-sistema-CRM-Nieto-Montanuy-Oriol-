<?php
function requiere_login() {
    if (!isset($_SESSION['id_usuario'])) {
        header('Location: index.php?ruta=login'); exit;
    }
}

function requiere_admin() {
    requiere_login();
    if ($_SESSION['rol'] != 'admin') {
        header('Location: index.php?ruta=dashboard'); exit;
    }
}

function login() {
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = usuario_autenticar(trim($_POST['usuario']), $_POST['password']);
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

function registro() {
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $datos = ['usuario'=>trim($_POST['usuario']), 'nombre'=>trim($_POST['nombre']),
            'apellidos'=>trim($_POST['apellidos']), 'email'=>trim($_POST['email']),
            'password'=>$_POST['password'], 'rol'=>'vendedor'];
        if (in_array('', [$datos['usuario'], $datos['nombre'], $datos['apellidos'], $datos['email'], $datos['password']], true)) {
            $error = 'Todos los campos son obligatorios.';
        } elseif (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $error = 'El email no es válido.';
        } elseif (!usuario_crear($datos)) {
            $error = 'El usuario o email ya están registrados.';
        } else {
            header('Location: index.php?ruta=login'); exit;
        }
    }
    require __DIR__ . '/../Vista/registro.php';
}

function logout() {
    $_SESSION = [];
    session_destroy();
    header('Location: index.php?ruta=login'); exit;
}

function dashboard() {
    requiere_login();
    $totalClientes = cliente_total();
    $oportunidades = oportunidad_resumen();
    $actividadesPendientes = actividad_pendientes();
    $usuarios = $_SESSION['rol'] == 'admin' ? usuario_todos() : [];
    require __DIR__ . '/../Vista/dashboard.php';
}

function usuarios() {
    requiere_admin();
    header('Location: index.php?ruta=dashboard');
    exit;
}

function guardar_usuario() {
    requiere_admin();
    $datos = ['id_usuario'=>(int)($_POST['id_usuario'] ?? 0), 'usuario'=>trim($_POST['usuario']), 'nombre'=>trim($_POST['nombre']),
        'apellidos'=>trim($_POST['apellidos']), 'email'=>trim($_POST['email']),
        'password'=>$_POST['password'], 'rol'=>$_POST['rol']];
    if ($datos['usuario'] === '' || $datos['nombre'] === '' || $datos['apellidos'] === '' || $datos['email'] === '') {
        $error = 'Los datos del usuario son obligatorios.';
    } elseif (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
        $error = 'El email no es válido.';
    } elseif (!in_array($datos['rol'], ['admin', 'vendedor'], true)) {
        $error = 'El rol no es válido.';
    } elseif ($datos['id_usuario'] > 0 && usuario_actualizar($datos)) {
        header('Location: index.php?ruta=usuarios'); exit;
    } elseif ($datos['id_usuario'] === 0 && $datos['password'] !== '' && usuario_crear($datos)) {
        header('Location: index.php?ruta=usuarios'); exit;
    } else {
        $error = 'No se ha podido crear el usuario.';
    }
    $usuarios = usuario_todos();
    $totalClientes = cliente_total();
    $oportunidades = oportunidad_resumen();
    $actividadesPendientes = actividad_pendientes();
    require __DIR__ . '/../Vista/dashboard.php';
}

function eliminar_usuario() {
    requiere_admin();
    if ((int)$_GET['id'] !== (int)$_SESSION['id_usuario']) {
        usuario_eliminar((int)$_GET['id'], $_SESSION['id_usuario']);
    }
    header('Location: index.php?ruta=usuarios'); exit;
}
