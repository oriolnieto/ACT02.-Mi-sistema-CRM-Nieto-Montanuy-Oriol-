<?php
require_once __DIR__ . '/conexion.php';

class Usuario {
    public static function autenticar($usuario, $password) {
        global $conexion;
        $stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ?");
        $stmt->bind_param('s', $usuario);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();
        return $fila && password_verify($password, $fila['password']) ? $fila : null;
    }

    public static function crear($datos) {
        global $conexion;
        $hash = password_hash($datos['password'], PASSWORD_DEFAULT);
        $stmt = $conexion->prepare("INSERT INTO usuarios (usuario,nombre,apellidos,email,password,rol) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param('ssssss', $datos['usuario'], $datos['nombre'], $datos['apellidos'], $datos['email'], $hash, $datos['rol']);
        return $stmt->execute();
    }

    public static function actualizar($datos) {
        global $conexion;

        if ($datos['password'] !== '') {
            $hash = password_hash($datos['password'], PASSWORD_DEFAULT);
            $stmt = $conexion->prepare("UPDATE usuarios SET usuario=?,nombre=?,apellidos=?,email=?,password=?,rol=? WHERE id_usuario=?");
            $stmt->bind_param('ssssssi', $datos['usuario'], $datos['nombre'], $datos['apellidos'], $datos['email'], $hash, $datos['rol'], $datos['id_usuario']);
        } else {
            $stmt = $conexion->prepare("UPDATE usuarios SET usuario=?,nombre=?,apellidos=?,email=?,rol=? WHERE id_usuario=?");
            $stmt->bind_param('sssssi', $datos['usuario'], $datos['nombre'], $datos['apellidos'], $datos['email'], $datos['rol'], $datos['id_usuario']);
        }

        return $stmt->execute();
    }

    public static function todos() {
        global $conexion;
        $resultado = $conexion->query("SELECT id_usuario,usuario,nombre,apellidos,email,rol FROM usuarios ORDER BY id_usuario");
        return $resultado ? $resultado->fetch_all(MYSQLI_ASSOC) : [];
    }

    public static function porId($id) {
        global $conexion;
        $stmt = $conexion->prepare("SELECT id_usuario,usuario,nombre,apellidos,email,rol FROM usuarios WHERE id_usuario = ?");
        $stmt->bind_param('i', $id); $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function eliminar($id, $usuarioReceptor) {
        global $conexion;

        $conexion->begin_transaction();

        try {
            $stmt = $conexion->prepare("UPDATE clientes SET usuario_responsable=? WHERE usuario_responsable=?");
            $stmt->bind_param('ii', $usuarioReceptor, $id);
            $stmt->execute();

            $stmt = $conexion->prepare("UPDATE oportunidades SET usuario_responsable=? WHERE usuario_responsable=?");
            $stmt->bind_param('ii', $usuarioReceptor, $id);
            $stmt->execute();

            $stmt = $conexion->prepare("UPDATE actividades SET usuario_responsable=? WHERE usuario_responsable=?");
            $stmt->bind_param('ii', $usuarioReceptor, $id);
            $stmt->execute();

            $stmt = $conexion->prepare("DELETE FROM usuarios WHERE id_usuario=?");
            $stmt->bind_param('i', $id);
            $stmt->execute();

            $conexion->commit();
            return true;
        } catch (mysqli_sql_exception $error) {
            $conexion->rollback();
            return false;
        }
    }
}
