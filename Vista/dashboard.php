<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CRM - Inicio</title>
</head>
<body>
    <nav>
        <a href="index.php?ruta=dashboard">Inicio</a> |
        <a href="index.php?ruta=clientes">Clientes</a> |
        <a href="index.php?ruta=oportunidades">Oportunidades</a> |
        <a href="index.php?ruta=actividades">Actividades</a>
        <?php if ($_SESSION['rol'] === 'admin') { ?> |
            <a href="index.php?ruta=usuarios">Usuarios</a>
        <?php } ?> |
        <a href="index.php?ruta=logout">Cerrar sesión</a>
    </nav>
    <hr>

    <h1>Panel de control</h1>
    <p>Bienvenido. Rol: <?= $_SESSION['rol'] ?></p>
    <ul>
        <li>Total de clientes: <?= $totalClientes ?></li>
        <li>Oportunidades en progreso: <?= $oportunidades['progreso'] ?></li>
        <li>Oportunidades ganadas: <?= $oportunidades['ganada'] ?></li>
        <li>Oportunidades perdidas: <?= $oportunidades['perdida'] ?></li>
        <li>Actividades pendientes: <?= $actividadesPendientes ?></li>
    </ul>

    <?php if ($_SESSION['rol'] === 'admin') { ?>
        <h2>Gestión de usuarios</h2>
        <?php if (isset($error)) { ?>
            <p><?= $error ?></p>
        <?php } ?>
        <form method="post" action="index.php?ruta=usuario-guardar">
            Usuario: <input type="text" name="usuario" required>
            Nombre: <input type="text" name="nombre" required>
            Apellidos: <input type="text" name="apellidos" required>
            Email: <input type="email" name="email" required>
            Contraseña: <input type="password" name="password" required>
            Rol:
            <select name="rol">
                <option value="vendedor">Vendedor</option>
                <option value="admin">Administrador</option>
            </select>
            <input type="submit" value="Crear usuario">
        </form>

        <table border="1">
            <tr>
                <th>Usuario</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acción</th>
            </tr>
            <?php foreach ($usuarios as $usuario) { ?>
                <tr>
                    <td><?= $usuario['usuario'] ?></td>
                    <td><?= $usuario['nombre'] . ' ' . $usuario['apellidos'] ?></td>
                    <td><?= $usuario['email'] ?></td>
                    <td><?= $usuario['rol'] ?></td>
                    <td>
                        <form method="post" action="index.php?ruta=usuario-guardar">
                            <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                            Usuario:
                            <input type="text" name="usuario" value="<?= $usuario['usuario'] ?>" required>
                            Nombre:
                            <input type="text" name="nombre" value="<?= $usuario['nombre'] ?>" required>
                            Apellidos:
                            <input type="text" name="apellidos" value="<?= $usuario['apellidos'] ?>" required>
                            Email:
                            <input type="email" name="email" value="<?= $usuario['email'] ?>" required>
                            Rol:
                            <select name="rol">
                                <option value="vendedor" <?= $usuario['rol'] === 'vendedor' ? 'selected' : '' ?>>
                                    Vendedor
                                </option>
                                <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>
                                    Administrador
                                </option>
                            </select>
                            Contraseña nueva:
                            <input type="password" name="password">
                            <input type="submit" value="Actualizar">
                        </form>
                        <?php if ($usuario['id_usuario'] != $_SESSION['id_usuario']) { ?>
                            <a href="index.php?ruta=usuario-eliminar&id=<?= $usuario['id_usuario'] ?>">
                                Eliminar
                            </a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>
</body>
</html>
