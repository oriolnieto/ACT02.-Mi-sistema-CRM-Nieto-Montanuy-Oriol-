<?php
session_start();

require_once __DIR__ . '/Modelo/usuario.php';
require_once __DIR__ . '/Modelo/cliente.php';
require_once __DIR__ . '/Modelo/oportunidad.php';
require_once __DIR__ . '/Modelo/actividad.php';
require_once __DIR__ . '/Controlador/authControlador.php';
require_once __DIR__ . '/Controlador/clientesControlador.php';
require_once __DIR__ . '/Controlador/oportunidadesControlador.php';
require_once __DIR__ . '/Controlador/actividadesControlador.php';

$ruta = isset($_GET['ruta']) ? $_GET['ruta'] : 'login';

switch ($ruta) {
    case 'login':
        (new AuthControlador())->login();
        break;
    case 'registro':
        (new AuthControlador())->registro();
        break;
    case 'logout':
        (new AuthControlador())->logout();
        break;
    case 'dashboard':
        (new AuthControlador())->dashboard();
        break;
    case 'usuarios':
        (new AuthControlador())->usuarios();
        break;
    case 'usuario-guardar':
        (new AuthControlador())->guardarUsuario();
        break;
    case 'usuario-eliminar':
        (new AuthControlador())->eliminarUsuario();
        break;
    case 'clientes':
        (new ClientesControlador())->listar();
        break;
    case 'cliente-guardar':
        (new ClientesControlador())->guardar();
        break;
    case 'cliente-eliminar':
        (new ClientesControlador())->eliminar();
        break;
    case 'oportunidades':
        (new OportunidadesControlador())->listar();
        break;
    case 'oportunidad-guardar':
        (new OportunidadesControlador())->guardar();
        break;
    case 'oportunidad-eliminar':
        (new OportunidadesControlador())->eliminar();
        break;
    case 'oportunidad-estado':
        (new OportunidadesControlador())->cambiarEstado();
        break;
    case 'actividades':
        (new ActividadesControlador())->listar();
        break;
    case 'actividad-guardar':
        (new ActividadesControlador())->guardar();
        break;
    case 'actividad-completar':
        (new ActividadesControlador())->completar();
        break;
    default:
        http_response_code(404);
        echo 'Página no encontrada!!';
}
