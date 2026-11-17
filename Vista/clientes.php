<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>PaddockCRM / Clientes</title>
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
    <h1>Clientes</h1>
    <form method="get">
        <input type="hidden" name="ruta" value="clientes">
        <input type="text" name="buscar" placeholder="Nombre o empresa"
               value="<?= isset($_GET['buscar']) ? $_GET['buscar'] : '' ?>">
        <input type="submit" value="Buscar">
    </form>
    <h2>Nuevo cliente</h2>
    <form method="post" action="index.php?ruta=cliente-guardar">
        Nombre completo: <input type="text" name="nombre_completo" required>
        Email: <input type="email" name="email" required>
        Teléfono: <input type="text" name="tlf" required>
        Empresa: <input type="text" name="empresa" required>
        <input type="submit" value="Guardar">
    </form>
    <?php foreach ($clientes as $cliente) { ?>
        <form id="cliente-<?= $cliente['id_cliente'] ?>" method="post" action="index.php?ruta=cliente-guardar"></form>
    <?php } ?>
    <table border="1">
        <tr>
            <th>Nombre</th><th>Email</th><th>Teléfono</th><th>Empresa</th>
            <th>Fecha</th><th>Responsable</th><th>Acción</th>
        </tr>
        <?php foreach ($clientes as $cliente) { ?>
            <tr>
                    <input form="cliente-<?= $cliente['id_cliente'] ?>" type="hidden" name="id_cliente" value="<?= $cliente['id_cliente'] ?>">
                    <td><input form="cliente-<?= $cliente['id_cliente'] ?>" type="text" name="nombre_completo" value="<?= $cliente['nombre_completo'] ?>"></td>
                    <td><input form="cliente-<?= $cliente['id_cliente'] ?>" type="email" name="email" value="<?= $cliente['email'] ?>"></td>
                    <td><input form="cliente-<?= $cliente['id_cliente'] ?>" type="text" name="tlf" value="<?= $cliente['tlf'] ?>"></td>
                    <td><input form="cliente-<?= $cliente['id_cliente'] ?>" type="text" name="empresa" value="<?= $cliente['empresa'] ?>"></td>
                    <td><?= $cliente['fecha_registro'] ?></td>
                    <td><?= $cliente['responsable'] ?></td>
                    <td>
                        <input form="cliente-<?= $cliente['id_cliente'] ?>" type="submit" value="Actualizar">
                        <a href="index.php?ruta=cliente-eliminar&id=<?= $cliente['id_cliente'] ?>">Eliminar</a>
                    </td>
            </tr>
        <?php }?>
    </table>
</body>
</html>
