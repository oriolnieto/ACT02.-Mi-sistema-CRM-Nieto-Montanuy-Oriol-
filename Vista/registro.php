<!DOCTYPE html><html><head><meta charset="utf-8"><title>PaddockCRM / Registro</title></head><body>
<h1>Registro de vendedor</h1>
<?php if ($error) echo '<p>' . $error . '</p>'; ?>
<form method="post" action="index.php?ruta=registro">
    Usuario: <input name="usuario" required><br>
    Nombre: <input name="nombre" required><br>
    Apellidos: <input name="apellidos" required><br>
    Email: <input type="email" name="email" required><br>
    Contraseña: <input type="password" name="password" required><br>
    <input type="submit" value="Registrarse">
</form>
<a href="index.php?ruta=login">Volver al login</a>
</body></html>
