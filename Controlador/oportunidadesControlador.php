<?php
class OportunidadesControlador {
    private $auth;
    private $cliente;
    private $oportunidad;

    public function __construct() {
        $this->auth = new AuthControlador();
        $this->cliente = new Cliente();
        $this->oportunidad = new Oportunidad();
    }

    public function listar() {
        $this->auth->requiereLogin();
        $admin = $_SESSION['rol'] == 'admin';
        $oportunidades = $this->oportunidad->todos($_SESSION['id_usuario'], $admin, isset($_GET['estado']) ? $_GET['estado'] : '', isset($_GET['cliente']) ? $_GET['cliente'] : '');
        $clientes = $this->cliente->todos($_SESSION['id_usuario'], $admin);
        require __DIR__ . '/../Vista/oportunidades.php';
    }

    public function guardar() {
        $this->auth->requiereLogin();
        $admin = $_SESSION['rol'] == 'admin';
        $clienteId = (int)$_POST['id_cliente'];

        if (!$this->cliente->puedeGestionar($clienteId, $_SESSION['id_usuario'], $admin)) {
            header('Location: index.php?ruta=oportunidades'); exit;
        }
        if (!in_array($_POST['estado'], ['progreso', 'ganada', 'perdida'], true)) {
            header('Location: index.php?ruta=oportunidades'); exit;
        }

        $datos = ['id_oportunidad'=>(int)($_POST['id_oportunidad'] ?? 0), 'id_cliente'=>(int)$_POST['id_cliente'],
            'titulo'=>trim($_POST['titulo']), 'descripcion'=>trim($_POST['descripcion']),
            'valor_estimado'=>(float)$_POST['valor_estimado'], 'estado'=>$_POST['estado'],
            'usuario_responsable'=>$_SESSION['id_usuario']];
        $this->oportunidad->guardar($datos, $_SESSION['id_usuario'], $admin);
        header('Location: index.php?ruta=oportunidades'); exit;
    }

    public function eliminar() {
        $this->auth->requiereLogin();
        $this->oportunidad->eliminar((int)$_GET['id'], $_SESSION['id_usuario'], $_SESSION['rol'] == 'admin');
        header('Location: index.php?ruta=oportunidades'); exit;
    }

    public function cambiarEstado() {
        $this->auth->requiereLogin();
        if (!in_array($_POST['estado'], ['progreso', 'ganada', 'perdida'], true)) {
            header('Location: index.php?ruta=oportunidades'); exit;
        }
        $this->oportunidad->cambiarEstado((int)$_POST['id'], $_POST['estado'], $_SESSION['id_usuario'], $_SESSION['rol'] == 'admin');
        header('Location: index.php?ruta=oportunidades'); exit;
    }
}
