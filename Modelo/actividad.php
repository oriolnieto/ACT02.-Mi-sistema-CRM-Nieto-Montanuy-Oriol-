<?php
require_once __DIR__ . '/conexion.php';

class Actividad {
    public function pendientes() {
        global $conexion;
        return (int)$conexion->query("SELECT COUNT(*) total FROM actividades WHERE estado='pendiente'")->fetch_assoc()['total'];
    }

    public function todos($usuarioId, $esAdmin) {
        global $conexion;
        $sql = "SELECT a.*, o.titulo oportunidad FROM actividades a LEFT JOIN oportunidades o ON o.id_oportunidad=a.id_oportunidad";
        if (!$esAdmin) $sql .= " WHERE a.usuario_responsable=$usuarioId";
        $sql .= " ORDER BY a.estado, a.fecha";
        $resultado = $conexion->query($sql);
        return $resultado->fetch_all(MYSQLI_ASSOC);
    }

    public function guardar($datos) {
        global $conexion;
        $oportunidad = $datos['id_oportunidad'] !== '' ? (int)$datos['id_oportunidad'] : 0;
        $cliente = $datos['id_cliente'] !== '' ? (int)$datos['id_cliente'] : 0;
        $oportunidadSql = $oportunidad ? $oportunidad : 'NULL';
        $clienteSql = $cliente ? $cliente : 'NULL';
        $sql = "INSERT INTO actividades (id_oportunidad,id_cliente,descripcion,fecha,estado,usuario_responsable) VALUES ($oportunidadSql,$clienteSql,'{$datos['descripcion']}','{$datos['fecha']}','pendiente',{$datos['usuario_responsable']})";
        return (bool)$conexion->query($sql);
    }

    public function completar($id, $usuarioId, $esAdmin) {
        global $conexion;
        $sql = $esAdmin
            ? "UPDATE actividades SET estado='completada' WHERE id_tarea=$id"
            : "UPDATE actividades SET estado='completada' WHERE id_tarea=$id AND usuario_responsable=$usuarioId";
        return (bool)$conexion->query($sql);
    }
}
