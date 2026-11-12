<?php
function actividades_listar() {
    requiere_login();
    $admin = $_SESSION['rol'] == 'admin';
    $actividades = actividad_todos($_SESSION['id_usuario'], $admin);
    $clientes = cliente_todos($_SESSION['id_usuario'], $admin);
    $oportunidades = oportunidad_todos($_SESSION['id_usuario'], $admin);
    require __DIR__ . '/../Vista/actividades.php';
}

function actividades_guardar() {
    requiere_login();
    $admin = $_SESSION['rol'] == 'admin';
    $oportunidadId = (int)($_POST['id_oportunidad'] ?? 0);
    $clienteId = (int)($_POST['id_cliente'] ?? 0);
    if ($oportunidadId === 0 && $clienteId === 0) {
        header('Location: index.php?ruta=actividades'); exit;
    }
    if ($oportunidadId > 0 && !oportunidad_puede_gestionar($oportunidadId, $_SESSION['id_usuario'], $admin)) {
        header('Location: index.php?ruta=actividades'); exit;
    }
    if ($clienteId > 0 && !cliente_puede_gestionar($clienteId, $_SESSION['id_usuario'], $admin)) {
        header('Location: index.php?ruta=actividades'); exit;
    }
    $datos = ['id_oportunidad'=>$_POST['id_oportunidad'], 'id_cliente'=>$_POST['id_cliente'],
        'descripcion'=>trim($_POST['descripcion']), 'fecha'=>$_POST['fecha'],
        'usuario_responsable'=>$_SESSION['id_usuario']];
    actividad_guardar($datos);
    header('Location: index.php?ruta=actividades'); exit;
}

function actividades_completar() {
    requiere_login();
    actividad_completar((int)$_GET['id'], $_SESSION['id_usuario'], $_SESSION['rol'] == 'admin');
    header('Location: index.php?ruta=actividades'); exit;
}
