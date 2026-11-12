<?php
class ClientesControlador {
    private $auth;
    private $cliente;

    public function __construct() {
        $this->auth = new AuthControlador();
        $this->cliente = new Cliente();
    }

    public function listar() {
        $this->auth->requiereLogin();
        $admin = $_SESSION['rol'] == 'admin';
        $clientes = $this->cliente->todos($_SESSION['id_usuario'], $admin, trim(isset($_GET['buscar']) ? $_GET['buscar'] : ''));
        require __DIR__ . '/../Vista/clientes.php';
    }

    public function guardar() {
        $this->auth->requiereLogin();
        $datos = ['id_cliente'=>(int)($_POST['id_cliente'] ?? 0), 'nombre_completo'=>trim($_POST['nombre_completo']),
            'email'=>trim($_POST['email']), 'tlf'=>trim($_POST['tlf']), 'empresa'=>trim($_POST['empresa']),
            'usuario_responsable'=>$_SESSION['id_usuario']];
        $this->cliente->guardar($datos, $_SESSION['id_usuario'], $_SESSION['rol'] == 'admin');
        header('Location: index.php?ruta=clientes'); exit;
    }

    public function eliminar() {
        $this->auth->requiereLogin();
        $this->cliente->eliminar((int)$_GET['id'], $_SESSION['id_usuario'], $_SESSION['rol'] == 'admin');
        header('Location: index.php?ruta=clientes'); exit;
    }
}
