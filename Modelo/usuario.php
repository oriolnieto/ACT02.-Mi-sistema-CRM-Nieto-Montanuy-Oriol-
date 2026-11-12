<?php
require_once __DIR__ . '/conexion.php';

function usuario_autenticar($usuario, $password) {
    global $conexion;
    $fila = mysqli_fetch_assoc(mysqli_query($conexion, "SELECT * FROM usuarios WHERE usuario = '$usuario'"));
    return $fila && password_verify($password, $fila['password']) ? $fila : null;
}

function usuario_crear($datos) {
    global $conexion;
    $hash = password_hash($datos['password'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (usuario,nombre,apellidos,email,password,rol) VALUES ('{$datos['usuario']}','{$datos['nombre']}','{$datos['apellidos']}','{$datos['email']}','$hash','{$datos['rol']}')";
    return (bool)mysqli_query($conexion, $sql);
}

function usuario_actualizar($datos) {
    global $conexion;
    if ($datos['password'] !== '') {
        $hash = password_hash($datos['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET usuario='{$datos['usuario']}',nombre='{$datos['nombre']}',apellidos='{$datos['apellidos']}',email='{$datos['email']}',password='$hash',rol='{$datos['rol']}' WHERE id_usuario={$datos['id_usuario']}";
    } else {
        $sql = "UPDATE usuarios SET usuario='{$datos['usuario']}',nombre='{$datos['nombre']}',apellidos='{$datos['apellidos']}',email='{$datos['email']}',rol='{$datos['rol']}' WHERE id_usuario={$datos['id_usuario']}";
    }
    return (bool)mysqli_query($conexion, $sql);
}

function usuario_todos() {
    global $conexion;
    $resultado = mysqli_query($conexion, "SELECT id_usuario,usuario,nombre,apellidos,email,rol FROM usuarios ORDER BY id_usuario");
    return $resultado ? mysqli_fetch_all($resultado, MYSQLI_ASSOC) : [];
}

function usuario_por_id($id) {
    global $conexion;
    return mysqli_fetch_assoc(mysqli_query($conexion, "SELECT id_usuario,usuario,nombre,apellidos,email,rol FROM usuarios WHERE id_usuario = $id"));
}

function usuario_eliminar($id, $usuarioReceptor) {
    global $conexion;
    mysqli_begin_transaction($conexion);
    try {
        mysqli_query($conexion, "UPDATE clientes SET usuario_responsable=$usuarioReceptor WHERE usuario_responsable=$id");
        mysqli_query($conexion, "UPDATE oportunidades SET usuario_responsable=$usuarioReceptor WHERE usuario_responsable=$id");
        mysqli_query($conexion, "UPDATE actividades SET usuario_responsable=$usuarioReceptor WHERE usuario_responsable=$id");
        mysqli_query($conexion, "DELETE FROM usuarios WHERE id_usuario=$id");
        mysqli_commit($conexion);
        return true;
    } catch (mysqli_sql_exception $error) {
        mysqli_rollback($conexion);
        return false;
    }
}
