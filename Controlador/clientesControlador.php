<?php
function clientes_listar() {
    requiere_login();
    $admin = $_SESSION['rol'] == 'admin';
    $clientes = cliente_todos($_SESSION['id_usuario'], $admin, trim(isset($_GET['buscar']) ? $_GET['buscar'] : ''));
    require __DIR__ . '/../Vista/clientes.php';
}

function clientes_guardar() {
    requiere_login();
    $datos = ['id_cliente'=>(int)($_POST['id_cliente'] ?? 0), 'nombre_completo'=>trim($_POST['nombre_completo']),
        'email'=>trim($_POST['email']), 'tlf'=>trim($_POST['tlf']), 'empresa'=>trim($_POST['empresa']),
        'usuario_responsable'=>$_SESSION['id_usuario']];
    cliente_guardar($datos, $_SESSION['id_usuario'], $_SESSION['rol'] == 'admin');
    header('Location: index.php?ruta=clientes'); exit;
}

function clientes_eliminar() {
    requiere_login();
    cliente_eliminar((int)$_GET['id'], $_SESSION['id_usuario'], $_SESSION['rol'] == 'admin');
    header('Location: index.php?ruta=clientes'); exit;
}
