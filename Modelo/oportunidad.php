<?php
require_once __DIR__ . '/conexion.php';

class Oportunidad {
    public function resumen() {
        global $conexion;
        $resultado = $conexion->query("SELECT estado, COUNT(*) total FROM oportunidades GROUP BY estado");
        $resumen = ['progreso'=>0, 'ganada'=>0, 'perdida'=>0];
        while ($fila = $resultado->fetch_assoc()) $resumen[$fila['estado']] = $fila['total'];
        return $resumen;
    }

    public function puedeGestionar($id, $usuarioId, $esAdmin) {
        global $conexion;
        $sql = $esAdmin
            ? "SELECT id_oportunidad FROM oportunidades WHERE id_oportunidad=$id"
            : "SELECT id_oportunidad FROM oportunidades WHERE id_oportunidad=$id AND usuario_responsable=$usuarioId";
        return (bool)$conexion->query($sql)->fetch_assoc();
    }

    public function todos($usuarioId, $esAdmin, $estado = '', $cliente = '') {
        global $conexion;
        $sql = "SELECT o.*, c.nombre_completo cliente FROM oportunidades o JOIN clientes c ON c.id_cliente=o.id_cliente WHERE 1=1";
        if (!$esAdmin) $sql .= " AND o.usuario_responsable=$usuarioId";
        if ($estado !== '') $sql .= " AND o.estado='$estado'";
        if ($cliente !== '') $sql .= " AND o.id_cliente=$cliente";
        $sql .= " ORDER BY o.id_oportunidad DESC";
        $resultado = $conexion->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function guardar($datos, $usuarioId, $esAdmin) {
        global $conexion;
        if (!empty($datos['id_oportunidad'])) {
            $sql = $esAdmin
                ? "UPDATE oportunidades SET id_cliente={$datos['id_cliente']},titulo='{$datos['titulo']}',descripcion='{$datos['descripcion']}',valor_estimado={$datos['valor_estimado']},estado='{$datos['estado']}' WHERE id_oportunidad={$datos['id_oportunidad']}"
                : "UPDATE oportunidades SET id_cliente={$datos['id_cliente']},titulo='{$datos['titulo']}',descripcion='{$datos['descripcion']}',valor_estimado={$datos['valor_estimado']},estado='{$datos['estado']}' WHERE id_oportunidad={$datos['id_oportunidad']} AND usuario_responsable=$usuarioId";
            return (bool)$conexion->query($sql);
        } else {
            $sql = "INSERT INTO oportunidades (id_cliente,titulo,descripcion,valor_estimado,estado,fecha_creacion,usuario_responsable) VALUES ({$datos['id_cliente']},'{$datos['titulo']}','{$datos['descripcion']}',{$datos['valor_estimado']},'{$datos['estado']}',CURDATE(),{$datos['usuario_responsable']})";
            return (bool)$conexion->query($sql);
        }
    }

    public function eliminar($id, $usuarioId, $esAdmin) {
        global $conexion;
        $sql = $esAdmin ? "DELETE FROM oportunidades WHERE id_oportunidad=$id" : "DELETE FROM oportunidades WHERE id_oportunidad=$id AND usuario_responsable=$usuarioId";
        return (bool)$conexion->query($sql);
    }

    public function cambiarEstado($id, $estado, $usuarioId, $esAdmin) {
        global $conexion;
        $sql = $esAdmin ? "UPDATE oportunidades SET estado='$estado' WHERE id_oportunidad=$id" : "UPDATE oportunidades SET estado='$estado' WHERE id_oportunidad=$id AND usuario_responsable=$usuarioId";
        return (bool)$conexion->query($sql);
    }
}
