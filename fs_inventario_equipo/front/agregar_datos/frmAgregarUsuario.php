<?php
    include_once("../../back/conexion/conexion.php");
    include_once("../../back/clases/Usuarios.php");
    include_once("../../back/clases/Roles.php");

    $listaEmpleados = Usuarios::empleadosDisponibles($conexion);
    $listaRoles = Roles::datosRoles($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Usuario</title>
    <link rel="stylesheet" href="../css/front.css">
</head>
<body>
    <header>
        <h1>Agregar Usuario</h1>
    </header>
    <main>
        <form action="../../back/objetos/agregar/agregarUsuario.php" method="post">
            <label for="lst_empleado">Empleado</label>
            <select name="lst_empleado" id="lst_empleado">
                <?php foreach($listaEmpleados as $emp): ?>
                    <option value="<?= $emp['empleado_id']; ?>"><?= $emp['nombre']; ?> <?= $emp['apellido']; ?></option>
                <?php endforeach ?>
            </select>
            <br>
            <label for="txt_usuario">Usuario</label>
            <input type="text" name="txt_usuario" id="txt_usuario">
            <br>
            <label for="txt_email">Email</label>
            <input type="email" name="txt_email" id="txt_email">
            <br>
            <label for="txt_pasword_hash">Contraseña / Hash</label>
            <input type="text" name="txt_pasword_hash" id="txt_pasword_hash">
            <br>
            <label for="txt_estado">Estado</label>
            <input type="text" name="txt_estado" id="txt_estado">
            <br>
            <label for="lst_rol">Rol</label>
            <select name="lst_rol" id="lst_rol">
                <?php foreach($listaRoles as $rol): ?>
                    <option value="<?= $rol['rol_id']; ?>"><?= $rol['nombre']; ?></option>
                <?php endforeach ?>
            </select>
            <br>
            <br>
            <button type="submit">Agregar Usuario</button>
        </form>
    </main>
</body>
</html>
