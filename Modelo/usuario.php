<?php
require_once __DIR__ . '/conexion.php';

class Usuario {
public static function autenticar($usuario, $password) {
    global $conexion;
    $fila = $conexion->query("SELECT * FROM usuarios WHERE usuario = '$usuario'")->fetch_assoc();
    return $fila && password_verify($password, $fila['password']) ? $fila : null;
}

public static function crear($datos) {
    global $conexion;
    $hash = password_hash($datos['password'], PASSWORD_DEFAULT);
    $sql = "INSERT INTO usuarios (usuario,nombre,apellidos,email,password,rol) VALUES ('{$datos['usuario']}','{$datos['nombre']}','{$datos['apellidos']}','{$datos['email']}','$hash','{$datos['rol']}')";
    return (bool)$conexion->query($sql);
}

public static function actualizar($datos) {
    global $conexion;
    if ($datos['password'] !== '') {
        $hash = password_hash($datos['password'], PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET usuario='{$datos['usuario']}',nombre='{$datos['nombre']}',apellidos='{$datos['apellidos']}',email='{$datos['email']}',password='$hash',rol='{$datos['rol']}' WHERE id_usuario={$datos['id_usuario']}";
    } else {
        $sql = "UPDATE usuarios SET usuario='{$datos['usuario']}',nombre='{$datos['nombre']}',apellidos='{$datos['apellidos']}',email='{$datos['email']}',rol='{$datos['rol']}' WHERE id_usuario={$datos['id_usuario']}";
    }
    return (bool)$conexion->query($sql);
}

public static function todos() {
    global $conexion;
    $resultado = $conexion->query("SELECT id_usuario,usuario,nombre,apellidos,email,rol FROM usuarios ORDER BY id_usuario");
    return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
}

public static function porId($id) {
    global $conexion;
    return $conexion->query("SELECT id_usuario,usuario,nombre,apellidos,email,rol FROM usuarios WHERE id_usuario = $id")->fetch_assoc();
}

public static function eliminar($id, $usuarioReceptor) {
    global $conexion;
    $conexion->begin_transaction();
    try {
        $conexion->query("UPDATE clientes SET usuario_responsable=$usuarioReceptor WHERE usuario_responsable=$id");
        $conexion->query("UPDATE oportunidades SET usuario_responsable=$usuarioReceptor WHERE usuario_responsable=$id");
        $conexion->query("UPDATE actividades SET usuario_responsable=$usuarioReceptor WHERE usuario_responsable=$id");
        $conexion->query("DELETE FROM usuarios WHERE id_usuario=$id");
        $conexion->commit();
        return true;
    } catch (mysqli_sql_exception $error) {
        $conexion->rollback();
        return false;
    }
    }
}
