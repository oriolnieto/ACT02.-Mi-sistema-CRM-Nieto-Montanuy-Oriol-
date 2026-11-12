<?php
require_once __DIR__ . '/conexion.php';

class Oportunidad {
    public static function resumen() {
        global $conexion;
        $resultado = $conexion->query("SELECT estado, COUNT(*) total FROM oportunidades GROUP BY estado");
        $resumen = ['progreso'=>0, 'ganada'=>0, 'perdida'=>0];
        while ($fila = $resultado->fetch_assoc()) $resumen[$fila['estado']] = $fila['total'];
        return $resumen;
    }

    public static function puedeGestionar($id, $usuarioId, $esAdmin) {
        global $conexion;
        $sql = $esAdmin
            ? "SELECT id_oportunidad FROM oportunidades WHERE id_oportunidad=?"
            : "SELECT id_oportunidad FROM oportunidades WHERE id_oportunidad=? AND usuario_responsable=?";
        $stmt = $conexion->prepare($sql);
        if ($esAdmin) {
            $stmt->bind_param('i', $id);
        } else {
            $stmt->bind_param('ii', $id, $usuarioId);
        }
        $stmt->execute();
        return (bool)$stmt->get_result()->fetch_assoc();
    }

    public static function todos($usuarioId, $esAdmin, $estado = '', $cliente = '') {
        global $conexion;
        $sql = "SELECT o.*, c.nombre_completo cliente FROM oportunidades o JOIN clientes c ON c.id_cliente=o.id_cliente WHERE 1=1";
        $tipos = ''; $valores = [];
        if (!$esAdmin) { $sql .= " AND o.usuario_responsable=?"; $tipos .= 'i'; $valores[] = $usuarioId; }
        if ($estado !== '') { $sql .= " AND o.estado=?"; $tipos .= 's'; $valores[] = $estado; }
        if ($cliente !== '') { $sql .= " AND o.id_cliente=?"; $tipos .= 'i'; $valores[] = (int)$cliente; }
        $sql .= " ORDER BY o.id_oportunidad DESC";
        $stmt = $conexion->prepare($sql);
        if ($esAdmin && $estado === '' && $cliente === '') {
        } elseif ($esAdmin && $estado === '' && $cliente !== '') $stmt->bind_param('i', $cliente);
        elseif ($esAdmin && $estado !== '' && $cliente === '') $stmt->bind_param('s', $estado);
        elseif ($esAdmin && $estado !== '' && $cliente !== '') $stmt->bind_param('si', $estado, $cliente);
        elseif (!$esAdmin && $estado === '' && $cliente === '') $stmt->bind_param('i', $usuarioId);
        elseif (!$esAdmin && $estado === '' && $cliente !== '') $stmt->bind_param('ii', $usuarioId, $cliente);
        elseif (!$esAdmin && $estado !== '' && $cliente === '') $stmt->bind_param('is', $usuarioId, $estado);
        else $stmt->bind_param('isi', $usuarioId, $estado, $cliente);
        $stmt->execute(); return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function guardar($datos, $usuarioId, $esAdmin) {
        global $conexion;
        if (!empty($datos['id_oportunidad'])) {
            $sql = $esAdmin
                ? "UPDATE oportunidades SET id_cliente=?,titulo=?,descripcion=?,valor_estimado=?,estado=? WHERE id_oportunidad=?"
                : "UPDATE oportunidades SET id_cliente=?,titulo=?,descripcion=?,valor_estimado=?,estado=? WHERE id_oportunidad=? AND usuario_responsable=?";
            $stmt = $conexion->prepare($sql);
            if ($esAdmin) {
                $stmt->bind_param('issdsi', $datos['id_cliente'], $datos['titulo'], $datos['descripcion'], $datos['valor_estimado'], $datos['estado'], $datos['id_oportunidad']);
            } else {
                $stmt->bind_param('issdsii', $datos['id_cliente'], $datos['titulo'], $datos['descripcion'], $datos['valor_estimado'], $datos['estado'], $datos['id_oportunidad'], $usuarioId);
            }
        } else {
            $sql = "INSERT INTO oportunidades (id_cliente,titulo,descripcion,valor_estimado,estado,fecha_creacion,usuario_responsable) VALUES ({$datos['id_cliente']},'{$datos['titulo']}','{$datos['descripcion']}',{$datos['valor_estimado']},'{$datos['estado']}',CURDATE(),{$datos['usuario_responsable']})";
            return (bool)$conexion->query($sql);
        }
        return $stmt->execute();
    }

    public static function eliminar($id, $usuarioId, $esAdmin) {
        global $conexion;
        $sql = $esAdmin ? "DELETE FROM oportunidades WHERE id_oportunidad=?" : "DELETE FROM oportunidades WHERE id_oportunidad=? AND usuario_responsable=?";
        $stmt = $conexion->prepare($sql);
        if ($esAdmin) $stmt->bind_param('i', $id); else $stmt->bind_param('ii', $id, $usuarioId);
        return $stmt->execute();
    }

    public static function cambiarEstado($id, $estado, $usuarioId, $esAdmin) {
        global $conexion;
        $sql = $esAdmin ? "UPDATE oportunidades SET estado=? WHERE id_oportunidad=?" : "UPDATE oportunidades SET estado=? WHERE id_oportunidad=? AND usuario_responsable=?";
        $stmt = $conexion->prepare($sql);
        if ($esAdmin) $stmt->bind_param('si', $estado, $id); else $stmt->bind_param('sii', $estado, $id, $usuarioId);
        return $stmt->execute();
    }
}
