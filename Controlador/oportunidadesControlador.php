<?php
function oportunidades_listar() {
    requiere_login();
    $admin = $_SESSION['rol'] == 'admin';
    $oportunidades = oportunidad_todos($_SESSION['id_usuario'], $admin, isset($_GET['estado']) ? $_GET['estado'] : '', isset($_GET['cliente']) ? $_GET['cliente'] : '');
    $clientes = cliente_todos($_SESSION['id_usuario'], $admin);
    require __DIR__ . '/../Vista/oportunidades.php';
}

function oportunidades_guardar() {
    requiere_login();
    $admin = $_SESSION['rol'] == 'admin';
    $clienteId = (int)$_POST['id_cliente'];
    if (!cliente_puede_gestionar($clienteId, $_SESSION['id_usuario'], $admin)) {
        header('Location: index.php?ruta=oportunidades'); exit;
    }
    if (!in_array($_POST['estado'], ['progreso', 'ganada', 'perdida'], true)) {
        header('Location: index.php?ruta=oportunidades'); exit;
    }
    $datos = ['id_oportunidad'=>(int)($_POST['id_oportunidad'] ?? 0), 'id_cliente'=>(int)$_POST['id_cliente'],
        'titulo'=>trim($_POST['titulo']), 'descripcion'=>trim($_POST['descripcion']),
        'valor_estimado'=>(float)$_POST['valor_estimado'], 'estado'=>$_POST['estado'],
        'usuario_responsable'=>$_SESSION['id_usuario']];
    oportunidad_guardar($datos, $_SESSION['id_usuario'], $admin);
    header('Location: index.php?ruta=oportunidades'); exit;
}

function oportunidades_eliminar() {
    requiere_login();
    oportunidad_eliminar((int)$_GET['id'], $_SESSION['id_usuario'], $_SESSION['rol'] == 'admin');
    header('Location: index.php?ruta=oportunidades'); exit;
}

function oportunidades_cambiar_estado() {
    requiere_login();
    if (!in_array($_POST['estado'], ['progreso', 'ganada', 'perdida'], true)) {
        header('Location: index.php?ruta=oportunidades'); exit;
    }
    oportunidad_cambiar_estado((int)$_POST['id'], $_POST['estado'], $_SESSION['id_usuario'], $_SESSION['rol'] == 'admin');
    header('Location: index.php?ruta=oportunidades'); exit;
}
