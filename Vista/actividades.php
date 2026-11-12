<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PaddockCRM / Actividades</title>
</head>
<body>
    <nav>
        <a href="index.php?ruta=dashboard">Inicio</a> |
        <a href="index.php?ruta=clientes">Clientes</a> |
        <a href="index.php?ruta=oportunidades">Oportunidades</a> |
        <a href="index.php?ruta=actividades">Actividades</a>
        <?php if ($_SESSION['rol'] === 'admin') { ?> |
            <a href="index.php?ruta=usuarios">Usuarios</a>
        <?php } ?>
        <a href="index.php?ruta=logout">Cerrar sesión</a>
    </nav>
    <hr>

    <h1>Actividades</h1>
    <h2>Nueva tarea</h2>
    <form method="post" action="index.php?ruta=actividad-guardar">
        Oportunidad:
        <select name="id_oportunidad">
            <option value="">Sin oportunidad</option>
            <?php foreach ($oportunidades as $oportunidad) { ?>
                <option value="<?= $oportunidad['id_oportunidad'] ?>">
                    <?= $oportunidad['titulo'] ?>
                </option>
            <?php } ?>
        </select>
        Cliente:
        <select name="id_cliente">
            <option value="">Sin cliente</option>
            <?php foreach ($clientes as $cliente) { ?>
                <option value="<?= $cliente['id_cliente'] ?>">
                    <?= $cliente['nombre_completo'] ?>
                </option>
            <?php } ?>
        </select>
        Descripción: <input type="text" name="descripcion" required>
        Fecha: <input type="date" name="fecha" required>
        <input type="submit" value="Guardar">
    </form>

    <table border="1">
        <tr>
            <th>Descripción</th>
            <th>Fecha</th>
            <th>Oportunidad</th>
            <th>Estado</th>
            <th>Acción</th>
        </tr>
        <?php foreach ($actividades as $actividad) { ?>
            <tr>
                <td><?= $actividad['descripcion'] ?></td>
                <td><?= $actividad['fecha'] ?></td>
                <td><?= $actividad['oportunidad'] ?: 'Sin oportunidad' ?></td>
                <td><?= $actividad['estado'] ?></td>
                <td>
                    <?php if ($actividad['estado'] === 'pendiente') { ?>
                        <a href="index.php?ruta=actividad-completar&id=<?= $actividad['id_tarea'] ?>">
                            Marcar completada
                        </a>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>





