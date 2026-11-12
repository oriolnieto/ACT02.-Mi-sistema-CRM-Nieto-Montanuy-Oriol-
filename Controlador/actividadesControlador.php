<?php
class ActividadesControlador {
    private $auth;
    private $actividad;
    private $cliente;
    private $oportunidad;

    public function __construct() {
        $this->auth = new AuthControlador();
        $this->actividad = new Actividad();
        $this->cliente = new Cliente();
        $this->oportunidad = new Oportunidad();
    }

    public function listar() {
        $this->auth->requiereLogin();
        $admin = $_SESSION['rol'] == 'admin';
        $actividades = $this->actividad->todos($_SESSION['id_usuario'], $admin);
        $clientes = $this->cliente->todos($_SESSION['id_usuario'], $admin);
        $oportunidades = $this->oportunidad->todos($_SESSION['id_usuario'], $admin);
        require __DIR__ . '/../Vista/actividades.php';
    }

    public function guardar() {
        $this->auth->requiereLogin();
        $admin = $_SESSION['rol'] == 'admin';
        $oportunidadId = (int)($_POST['id_oportunidad'] ?? 0);
        $clienteId = (int)($_POST['id_cliente'] ?? 0);
        if ($oportunidadId === 0 && $clienteId === 0) {
            header('Location: index.php?ruta=actividades'); exit;
        }
        if ($oportunidadId > 0 && !$this->oportunidad->puedeGestionar($oportunidadId, $_SESSION['id_usuario'], $admin)) {
            header('Location: index.php?ruta=actividades'); exit;
        }
        if ($clienteId > 0 && !$this->cliente->puedeGestionar($clienteId, $_SESSION['id_usuario'], $admin)) {
            header('Location: index.php?ruta=actividades'); exit;
        }
        $datos = ['id_oportunidad'=>$_POST['id_oportunidad'], 'id_cliente'=>$_POST['id_cliente'],
            'descripcion'=>trim($_POST['descripcion']), 'fecha'=>$_POST['fecha'],
            'usuario_responsable'=>$_SESSION['id_usuario']];
        $this->actividad->guardar($datos);
        header('Location: index.php?ruta=actividades'); exit;
    }

    public function completar() {
        $this->auth->requiereLogin();
        $this->actividad->completar((int)$_GET['id'], $_SESSION['id_usuario'], $_SESSION['rol'] == 'admin');
        header('Location: index.php?ruta=actividades'); exit;
    }
}
