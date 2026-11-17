<?php
class OportunidadesControlador {
    public function listar() {
        AuthControlador::requiereLogin();
        $admin = $_SESSION['rol'] == 'admin';
        $estadoFiltro = $_GET['estado'] ?? '';
        $clienteFiltro = $_GET['cliente'] ?? '';
        $oportunidades = Oportunidad::todos($_SESSION['id_usuario'], $admin, $estadoFiltro, $clienteFiltro);
        $clientes = Cliente::todos($_SESSION['id_usuario'], $admin);
        require __DIR__ . '/../Vista/oportunidades.php';
    }

    public function guardar() {
        AuthControlador::requiereLogin();
        $admin = $_SESSION['rol'] == 'admin';
        $clienteId = (int)($_POST['id_cliente'] ?? 0);
        if (!Cliente::puedeGestionar($clienteId, $_SESSION['id_usuario'], $admin)) {
            header('Location: index.php?ruta=oportunidades'); exit;
        }
        $estado = $_POST['estado'] ?? '';
        if (!in_array($estado, ['progreso', 'ganada', 'perdida'], true)) {
            header('Location: index.php?ruta=oportunidades'); exit;
        }
        $datos = ['id_oportunidad'=>(int)($_POST['id_oportunidad'] ?? 0), 'id_cliente'=>$clienteId,
            'titulo'=>trim($_POST['titulo'] ?? ''), 'descripcion'=>trim($_POST['descripcion'] ?? ''),
            'valor_estimado'=>(float)($_POST['valor_estimado'] ?? 0), 'estado'=>$estado,
            'usuario_responsable'=>$_SESSION['id_usuario']];
        Oportunidad::guardar($datos, $_SESSION['id_usuario'], $admin);
        header('Location: index.php?ruta=oportunidades'); exit;
    }

    public function eliminar() {
        AuthControlador::requiereLogin();
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            Oportunidad::eliminar($id, $_SESSION['id_usuario'], $_SESSION['rol'] == 'admin');
        }
        header('Location: index.php?ruta=oportunidades'); exit;
    }

    public function cambiarEstado() {
        AuthControlador::requiereLogin();
        $estado = $_POST['estado'] ?? '';
        if (!in_array($estado, ['progreso', 'ganada', 'perdida'], true)) {
            header('Location: index.php?ruta=oportunidades'); exit;
        }
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            Oportunidad::cambiarEstado($id, $estado, $_SESSION['id_usuario'], $_SESSION['rol'] == 'admin');
        }
        header('Location: index.php?ruta=oportunidades'); exit;
    }
}
