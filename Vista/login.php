<!DOCTYPE html><html><head><meta charset="utf-8"><title>CRM - Login</title></head><body>
<h1>Iniciar sesión</h1>
<?php if ($error) echo '<p>' . $error . '</p>'; ?>
<form method="post" action="index.php?ruta=login">
    Usuario: <input type="text" name="usuario" required><br>
    Contraseña: <input type="password" name="password" required><br>
    <input type="submit" value="Entrar">
</form>
<a href="index.php?ruta=registro">Crear una cuenta de vendedor</a>
</body></html>