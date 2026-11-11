<?php
class ActividadesControlador {
    public function listar() {
        AuthControlador::requiereLogin();
        $admin = $_SESSION['rol'] === 'admin';
        $actividades = Actividad::todos($_SESSION['id_usuario'], $admin);
        $clientes = Cliente::todos($_SESSION['id_usuario'], $admin);
        $oportunidades = Oportunidad::todos($_SESSION['id_usuario'], $admin);
        require __DIR__ . '/../Vista/actividades.php';
    }

    public function guardar() {
        AuthControlador::requiereLogin();
        $admin = $_SESSION['rol'] === 'admin';
        $oportunidadId = (int)($_POST['id_oportunidad'] ?? 0);
        $clienteId = (int)($_POST['id_cliente'] ?? 0);

        if ($oportunidadId === 0 && $clienteId === 0) {
            header('Location: index.php?ruta=actividades');
            exit;
        }

        if ($oportunidadId > 0 && !Oportunidad::puedeGestionar($oportunidadId, $_SESSION['id_usuario'], $admin)) {
            header('Location: index.php?ruta=actividades');
            exit;
        }

        if ($clienteId > 0 && !Cliente::puedeGestionar($clienteId, $_SESSION['id_usuario'], $admin)) {
            header('Location: index.php?ruta=actividades');
            exit;
        }

        $datos = ['id_oportunidad'=>$_POST['id_oportunidad'], 'id_cliente'=>$_POST['id_cliente'],
            'descripcion'=>trim($_POST['descripcion']), 'fecha'=>$_POST['fecha'],
            'usuario_responsable'=>$_SESSION['id_usuario']];
        Actividad::guardar($datos);
        header('Location: index.php?ruta=actividades'); exit;
    }

    public function completar() {
        AuthControlador::requiereLogin();
        Actividad::completar((int)$_GET['id'], $_SESSION['id_usuario'], $_SESSION['rol'] === 'admin');
        header('Location: index.php?ruta=actividades'); exit;
    }
}
