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
        login();
        break;
    case 'registro':
        registro();
        break;
    case 'logout':
        logout();
        break;
    case 'dashboard':
        dashboard();
        break;
    case 'usuarios':
        usuarios();
        break;
    case 'usuario-guardar':
        guardar_usuario();
        break;
    case 'usuario-eliminar':
        eliminar_usuario();
        break;
    case 'clientes':
        clientes_listar();
        break;
    case 'cliente-guardar':
        clientes_guardar();
        break;
    case 'cliente-eliminar':
        clientes_eliminar();
        break;
    case 'oportunidades':
        oportunidades_listar();
        break;
    case 'oportunidad-guardar':
        oportunidades_guardar();
        break;
    case 'oportunidad-eliminar':
        oportunidades_eliminar();
        break;
    case 'oportunidad-estado':
        oportunidades_cambiar_estado();
        break;
    case 'actividades':
        actividades_listar();
        break;
    case 'actividad-guardar':
        actividades_guardar();
        break;
    case 'actividad-completar':
        actividades_completar();
        break;
}
