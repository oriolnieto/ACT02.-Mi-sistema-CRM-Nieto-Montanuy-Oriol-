<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PaddockCRM / Oportunidades</title>
</head>
<body>
    <nav>
        <a href="index.php?ruta=dashboard">Inicio</a> |
        <a href="index.php?ruta=clientes">Clientes</a> |
        <a href="index.php?ruta=oportunidades">Oportunidades</a> |
        <a href="index.php?ruta=actividades">Actividades</a>
        <?php if ($_SESSION['rol'] == 'admin') { ?> |
            <a href="index.php?ruta=usuarios">Usuarios</a>
        <?php } ?> |
        <a href="index.php?ruta=logout">Cerrar sesión</a>
    </nav>
    <hr>

    <h1>Oportunidades</h1>
    <form method="get">
        <input type="hidden" name="ruta" value="oportunidades">
        Estado:
        <select name="estado">
            <option value="" <?= $estadoFiltro === '' ? 'selected' : '' ?>>Todos</option>
            <option value="progreso" <?= $estadoFiltro === 'progreso' ? 'selected' : '' ?>>Progreso</option>
            <option value="ganada" <?= $estadoFiltro === 'ganada' ? 'selected' : '' ?>>Ganada</option>
            <option value="perdida" <?= $estadoFiltro === 'perdida' ? 'selected' : '' ?>>Perdida</option>
        </select>
        Cliente:
        <select name="cliente">
            <option value="" <?= $clienteFiltro === '' ? 'selected' : '' ?>>Todos</option>
            <?php foreach ($clientes as $cliente) { ?>
                <option value="<?= $cliente['id_cliente'] ?>"
                    <?= (string)$cliente['id_cliente'] === (string)$clienteFiltro ? 'selected' : '' ?>>
                    <?= $cliente['nombre_completo'] ?>
                </option>
            <?php } ?>
        </select>
        <input type="submit" value="Filtrar">
    </form>

    <h2>Nueva oportunidad</h2>
    <form method="post" action="index.php?ruta=oportunidad-guardar">
        Cliente:
        <select name="id_cliente" required>
            <?php foreach ($clientes as $cliente) { ?>
                <option value="<?= $cliente['id_cliente'] ?>">
                    <?= $cliente['nombre_completo'] ?>
                </option>
            <?php } ?>
        </select>
        Título: <input type="text" name="titulo" required>
        Valor (€): <input type="number" step="0.01" name="valor_estimado" required>
        Estado:
        <select name="estado">
            <option value="progreso">Progreso</option>
            <option value="ganada">Ganada</option>
            <option value="perdida">Perdida</option>
        </select>
        Descripción: <input type="text" name="descripcion" required>
        <input type="submit" value="Guardar">
    </form>

    <?php foreach ($oportunidades as $oportunidad) { ?>
        <form id="oportunidad-<?= $oportunidad['id_oportunidad'] ?>" method="post" action="index.php?ruta=oportunidad-guardar"></form>
    <?php } ?>
    <table border="1">
        <tr>
            <th>Cliente</th>
            <th>Título</th>
            <th>Valor</th>
            <th>Estado</th>
            <th>Fecha</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($oportunidades as $oportunidad) { ?>
            <tr>
                    <input form="oportunidad-<?= $oportunidad['id_oportunidad'] ?>" type="hidden" name="id_oportunidad"
                           value="<?= $oportunidad['id_oportunidad'] ?>">
                    <td>
                        <select form="oportunidad-<?= $oportunidad['id_oportunidad'] ?>" name="id_cliente">
                            <?php foreach ($clientes as $cliente) { ?>
                                <option value="<?= $cliente['id_cliente'] ?>"
                                    <?= $cliente['id_cliente'] == $oportunidad['id_cliente'] ? 'selected' : '' ?>>
                                    <?= $cliente['nombre_completo'] ?>
                                </option>
                            <?php } ?>
                        </select>
                    </td>
                    <td>
                        <input form="oportunidad-<?= $oportunidad['id_oportunidad'] ?>" type="text" name="titulo"
                               value="<?= $oportunidad['titulo'] ?>">
                    </td>
                    <td>
                        <input form="oportunidad-<?= $oportunidad['id_oportunidad'] ?>" type="number" step="0.01" name="valor_estimado"
                               value="<?= $oportunidad['valor_estimado'] ?>">
                    </td>
                    <td>
                        <select form="oportunidad-<?= $oportunidad['id_oportunidad'] ?>" name="estado">
                            <option value="progreso"
                                <?= $oportunidad['estado'] === 'progreso' ? 'selected' : '' ?>>
                                Progreso
                            </option>
                            <option value="ganada"
                                <?= $oportunidad['estado'] === 'ganada' ? 'selected' : '' ?>>
                                Ganada
                            </option>
                            <option value="perdida"
                                <?= $oportunidad['estado'] === 'perdida' ? 'selected' : '' ?>>
                                Perdida
                            </option>
                        </select>
                    </td>
                    <td><?= $oportunidad['fecha_creacion'] ?></td>
                    <td>
                        <input form="oportunidad-<?= $oportunidad['id_oportunidad'] ?>" type="text" name="descripcion"
                               value="<?= $oportunidad['descripcion'] ?>">
                        <input form="oportunidad-<?= $oportunidad['id_oportunidad'] ?>" type="submit" value="Actualizar">
                        <a href="index.php?ruta=oportunidad-eliminar&id=<?= $oportunidad['id_oportunidad'] ?>">
                            Eliminar
                        </a>
                    </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
