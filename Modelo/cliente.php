<?php
require_once __DIR__ . '/conexion.php';

function cliente_total() {
    global $conexion;
    $resultado = mysqli_query($conexion, "SELECT COUNT(*) total FROM clientes");
    return (int)mysqli_fetch_assoc($resultado)['total'];
}

function cliente_todos($usuarioId, $esAdmin, $busqueda = '') {
    global $conexion;
    $texto = '%' . $busqueda . '%';
    $sql = "SELECT c.*, CONCAT(u.nombre, ' ', u.apellidos) responsable FROM clientes c JOIN usuarios u ON u.id_usuario=c.usuario_responsable WHERE (c.nombre_completo LIKE '$texto' OR c.empresa LIKE '$texto')";
    if (!$esAdmin) $sql .= " AND c.usuario_responsable = $usuarioId";
    $sql .= " ORDER BY c.id_cliente DESC";
    return mysqli_fetch_all(mysqli_query($conexion, $sql), MYSQLI_ASSOC);
}

function cliente_guardar($datos, $usuarioId, $esAdmin) {
    global $conexion;
    if (!empty($datos['id_cliente'])) {
        $sql = $esAdmin
            ? "UPDATE clientes SET nombre_completo='{$datos['nombre_completo']}',email='{$datos['email']}',tlf='{$datos['tlf']}',empresa='{$datos['empresa']}' WHERE id_cliente={$datos['id_cliente']}"
            : "UPDATE clientes SET nombre_completo='{$datos['nombre_completo']}',email='{$datos['email']}',tlf='{$datos['tlf']}',empresa='{$datos['empresa']}' WHERE id_cliente={$datos['id_cliente']} AND usuario_responsable=$usuarioId";
        return (bool)mysqli_query($conexion, $sql);
    }

    $sql = "INSERT INTO clientes (nombre_completo,email,tlf,empresa,fecha_registro,usuario_responsable) VALUES ('{$datos['nombre_completo']}','{$datos['email']}','{$datos['tlf']}','{$datos['empresa']}',CURDATE(),{$datos['usuario_responsable']})";
    return (bool)mysqli_query($conexion, $sql);
}

function cliente_puede_gestionar($id, $usuarioId, $esAdmin) {
    global $conexion;
    $sql = $esAdmin
        ? "SELECT id_cliente FROM clientes WHERE id_cliente=$id"
        : "SELECT id_cliente FROM clientes WHERE id_cliente=$id AND usuario_responsable=$usuarioId";
    return (bool)mysqli_fetch_assoc(mysqli_query($conexion, $sql));
}

function cliente_eliminar($id, $usuarioId, $esAdmin) {
    global $conexion;
    $sql = $esAdmin ? "DELETE FROM clientes WHERE id_cliente=$id" : "DELETE FROM clientes WHERE id_cliente=$id AND usuario_responsable=$usuarioId";
    return (bool)mysqli_query($conexion, $sql);
}
