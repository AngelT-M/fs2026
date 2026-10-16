<?php
    include_once("../../back/conexion/conexion.php");
    include_once("../../back/clases/Usuarios.php");
    include_once("../../back/clases/Roles.php");
    $listaRoles = Roles::datosRoles($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Usuario</title>
    <link rel="stylesheet" href="../css/front.css">
</head>
<body>
    <header>
        <h1>Modificar Usuario</h1>
    </header>
    <main>
        <form action="../../back/objetos/modificar/modificarUsuario.php" method="get">
            <input type="hidden" id="txt_usuario_id" name="txt_usuario_id" value="<?= $_GET['txt_usuario_id']?>">
            <br>
            <label for="txt_empleado">Empleado</label>
            <input type="text" id="txt_empleado" value="<?= $_GET['txt_empleado_nombre'] . ' ' . $_GET['txt_empleado_apellido']; ?>" readonly>
            <input type="hidden" name="txt_empleado_nombre" value="<?= $_GET['txt_empleado_nombre']; ?>">
            <input type="hidden" name="txt_empleado_apellido" value="<?= $_GET['txt_empleado_apellido']; ?>">
            <br>
            <label for="txt_usuario">Usuario</label>
            <input type="text" id="txt_usuario" name="txt_usuario" value="<?= $_GET['txt_usuario']?>">
            <br>
            <label for="txt_email">Email</label>
            <input type="email" id="txt_email" name="txt_email" value="<?= $_GET['txt_email']?>">
            <br>
            <label for="txt_pasword_hash">Contraseña / Hash</label>
            <input type="text" id="txt_pasword_hash" name="txt_pasword_hash" value="<?= $_GET['txt_pasword_hash']?>">
            <br>
            <label for="txt_estado">Estado</label>
            <input type="text" id="txt_estado" name="txt_estado" value="<?= $_GET['txt_estado']?>">
            <br>
            <label for="lst_rol">Rol</label>
            <select name="lst_rol" id="lst_rol">
                <?php foreach($listaRoles as $rol): ?>
                    <option value="<?= $rol['rol_id']; ?>" <?php if($rol['rol_id'] == $_GET['txt_rol_id']) { echo "selected"; } ?>>
                        <?= $rol['nombre']; ?>
                    </option>
                <?php endforeach ?>
            </select>
            <br>
            <br>
            <button type="submit">Actualizar</button>
        </form>
    </main>
</body>
</html>
