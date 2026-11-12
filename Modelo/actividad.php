<?php
require_once __DIR__ . '/conexion.php';

function actividad_pendientes() {
    global $conexion;
    $resultado = mysqli_query($conexion, "SELECT COUNT(*) total FROM actividades WHERE estado='pendiente'");
    return (int)mysqli_fetch_assoc($resultado)['total'];
}

function actividad_todos($usuarioId, $esAdmin) {
    global $conexion;
    $sql = "SELECT a.*, o.titulo oportunidad FROM actividades a LEFT JOIN oportunidades o ON o.id_oportunidad=a.id_oportunidad";
    if (!$esAdmin) $sql .= " WHERE a.usuario_responsable=$usuarioId";
    $sql .= " ORDER BY a.estado, a.fecha";
    return mysqli_fetch_all(mysqli_query($conexion, $sql), MYSQLI_ASSOC);
}

function actividad_guardar($datos) {
    global $conexion;
    $oportunidad = $datos['id_oportunidad'] !== '' ? (int)$datos['id_oportunidad'] : 0;
    $cliente = $datos['id_cliente'] !== '' ? (int)$datos['id_cliente'] : 0;
    $oportunidadSql = $oportunidad ? $oportunidad : 'NULL';
    $clienteSql = $cliente ? $cliente : 'NULL';
    $sql = "INSERT INTO actividades (id_oportunidad,id_cliente,descripcion,fecha,estado,usuario_responsable) VALUES ($oportunidadSql,$clienteSql,'{$datos['descripcion']}','{$datos['fecha']}','pendiente',{$datos['usuario_responsable']})";
    return (bool)mysqli_query($conexion, $sql);
}

function actividad_completar($id, $usuarioId, $esAdmin) {
    global $conexion;
    $sql = $esAdmin
        ? "UPDATE actividades SET estado='completada' WHERE id_tarea=$id"
        : "UPDATE actividades SET estado='completada' WHERE id_tarea=$id AND usuario_responsable=$usuarioId";
    return (bool)mysqli_query($conexion, $sql);
}
