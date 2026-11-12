<?php
class OportunidadesControlador {
    public function listar() {
        AuthControlador::requiereLogin();
        $admin = $_SESSION['rol'] == 'admin';
        $oportunidades = Oportunidad::todos($_SESSION['id_usuario'], $admin, isset($_GET['estado']) ? $_GET['estado'] : '', isset($_GET['cliente']) ? $_GET['cliente'] : '');
        $clientes = Cliente::todos($_SESSION['id_usuario'], $admin);
        require __DIR__ . '/../Vista/oportunidades.php';
    }

    public function guardar() {
        AuthControlador::requiereLogin();
        $admin = $_SESSION['rol'] == 'admin';
        $clienteId = (int)$_POST['id_cliente'];
        if (!Cliente::puedeGestionar($clienteId, $_SESSION['id_usuario'], $admin)) {
            header('Location: index.php?ruta=oportunidades'); exit;
        }
        if (!in_array($_POST['estado'], ['progreso', 'ganada', 'perdida'], true)) {
            header('Location: index.php?ruta=oportunidades'); exit;
        }
        $datos = ['id_oportunidad'=>(int)($_POST['id_oportunidad'] ?? 0), 'id_cliente'=>(int)$_POST['id_cliente'],
            'titulo'=>trim($_POST['titulo']), 'descripcion'=>trim($_POST['descripcion']),
            'valor_estimado'=>(float)$_POST['valor_estimado'], 'estado'=>$_POST['estado'],
            'usuario_responsable'=>$_SESSION['id_usuario']];
        Oportunidad::guardar($datos, $_SESSION['id_usuario'], $admin);
        header('Location: index.php?ruta=oportunidades'); exit;
    }

    public function eliminar() {
        AuthControlador::requiereLogin();
        Oportunidad::eliminar((int)$_GET['id'], $_SESSION['id_usuario'], $_SESSION['rol'] == 'admin');
        header('Location: index.php?ruta=oportunidades'); exit;
    }

    public function cambiarEstado() {
        AuthControlador::requiereLogin();
        if (!in_array($_POST['estado'], ['progreso', 'ganada', 'perdida'], true)) {
            header('Location: index.php?ruta=oportunidades'); exit;
        }
        Oportunidad::cambiarEstado((int)$_POST['id'], $_POST['estado'], $_SESSION['id_usuario'], $_SESSION['rol'] == 'admin');
        header('Location: index.php?ruta=oportunidades'); exit;
    }
}
