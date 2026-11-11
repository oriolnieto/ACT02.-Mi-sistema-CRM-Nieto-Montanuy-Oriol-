<?php
class ClientesControlador {
    public function listar() {
        AuthControlador::requiereLogin();
        $admin = $_SESSION['rol'] === 'admin';
        $clientes = Cliente::todos($_SESSION['id_usuario'], $admin, trim(isset($_GET['buscar']) ? $_GET['buscar'] : ''));
        require __DIR__ . '/../Vista/clientes.php';
    }

    public function guardar() {
        AuthControlador::requiereLogin();
        $datos = ['id_cliente'=>(int)($_POST['id_cliente'] ?? 0), 'nombre_completo'=>trim($_POST['nombre_completo']),
            'email'=>trim($_POST['email']), 'tlf'=>trim($_POST['tlf']), 'empresa'=>trim($_POST['empresa']),
            'usuario_responsable'=>$_SESSION['id_usuario']];
        Cliente::guardar($datos, $_SESSION['id_usuario'], $_SESSION['rol'] === 'admin');
        header('Location: index.php?ruta=clientes'); exit;
    }

    public function eliminar() {
        AuthControlador::requiereLogin();
        Cliente::eliminar((int)$_GET['id'], $_SESSION['id_usuario'], $_SESSION['rol'] === 'admin');
        header('Location: index.php?ruta=clientes'); exit;
    }
}
