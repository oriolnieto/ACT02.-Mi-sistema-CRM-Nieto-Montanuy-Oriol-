<?php
require_once __DIR__ . '/conexion.php';

class Cliente {
    public static function total() {
        global $conexion;
        return (int)$conexion->query("SELECT COUNT(*) total FROM clientes")->fetch_assoc()['total'];
    }

    public static function todos($usuarioId, $esAdmin, $busqueda = '') {
        global $conexion;
        $sql = "SELECT c.*, CONCAT(u.nombre, ' ', u.apellidos) responsable FROM clientes c JOIN usuarios u ON u.id_usuario=c.usuario_responsable WHERE (c.nombre_completo LIKE ? OR c.empresa LIKE ?)";
        if (!$esAdmin) $sql .= " AND c.usuario_responsable = ?";
        $sql .= " ORDER BY c.id_cliente DESC";
        $stmt = $conexion->prepare($sql); $texto = '%' . $busqueda . '%';
        if ($esAdmin) $stmt->bind_param('ss', $texto, $texto);
        else $stmt->bind_param('ssi', $texto, $texto, $usuarioId);
        $stmt->execute(); return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function guardar($datos, $usuarioId, $esAdmin) {
        global $conexion;
        if (!empty($datos['id_cliente'])) {
            $sql = $esAdmin
                ? "UPDATE clientes SET nombre_completo=?,email=?,tlf=?,empresa=? WHERE id_cliente=?"
                : "UPDATE clientes SET nombre_completo=?,email=?,tlf=?,empresa=? WHERE id_cliente=? AND usuario_responsable=?";
            $stmt = $conexion->prepare($sql);
            if ($esAdmin) {
                $stmt->bind_param('ssssi', $datos['nombre_completo'], $datos['email'], $datos['tlf'], $datos['empresa'], $datos['id_cliente']);
            } else {
                $stmt->bind_param('ssssii', $datos['nombre_completo'], $datos['email'], $datos['tlf'], $datos['empresa'], $datos['id_cliente'], $usuarioId);
            }
        } else {
            $sql = "INSERT INTO clientes (nombre_completo,email,tlf,empresa,fecha_registro,usuario_responsable) VALUES ('{$datos['nombre_completo']}','{$datos['email']}','{$datos['tlf']}','{$datos['empresa']}',CURDATE(),{$datos['usuario_responsable']})";
            return (bool)$conexion->query($sql);
        }
        return $stmt->execute();
    }

    public static function puedeGestionar($id, $usuarioId, $esAdmin) {
        global $conexion;
        $sql = $esAdmin
            ? "SELECT id_cliente FROM clientes WHERE id_cliente=?"
            : "SELECT id_cliente FROM clientes WHERE id_cliente=? AND usuario_responsable=?";
        $stmt = $conexion->prepare($sql);
        if ($esAdmin) {
            $stmt->bind_param('i', $id);
        } else {
            $stmt->bind_param('ii', $id, $usuarioId);
        }
        $stmt->execute();
        return (bool)$stmt->get_result()->fetch_assoc();
    }

    public static function eliminar($id, $usuarioId, $esAdmin) {
        global $conexion;
        $sql = $esAdmin ? "DELETE FROM clientes WHERE id_cliente=?" : "DELETE FROM clientes WHERE id_cliente=? AND usuario_responsable=?";
        $stmt = $conexion->prepare($sql);
        if ($esAdmin) $stmt->bind_param('i', $id); else $stmt->bind_param('ii', $id, $usuarioId);
        return $stmt->execute();
    }
}
