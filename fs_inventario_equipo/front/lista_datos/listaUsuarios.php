<?php
    include_once("../../back/conexion/conexion.php");
    include_once("../../back/clases/Usuarios.php");

    $listaUsuarios = Usuarios::datosUsuarios($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Usuarios</title>
    <link rel="stylesheet" href="../css/front.css">
    <link rel="stylesheet" href="../css/estilotabla.css">
</head>
<body>
    <header>
        <h1>Datos Usuarios</h1>
    </header>
    <nav>
        <div class="barra">
            <div class="barra_logo">
                <div>
                    <a href="../index.php">Logotipo</a>
                </div>
            </div>
            <div class="barra_link">
                <div><a href="listaEquipos.php">Equipos</a></div>
                <div><a href="listaMarcas.php">Marcas</a></div>
                <div><a href="listaTipos.php">Tipo de Equipo</a></div>
                <div><a href="listaEmpleados.php">Empleado</a></div>
                <div><a href="listaPuestos.php">Puesto</a></div>
                <div><a href="listaUsuarios.php">Usuario</a></div>
                <div><a href="listaRoles.php">Rol</a></div>
            </div>
        </div>
    </nav>
    <main>
        <div>
            <div>
                <a href="../index.php">Regresar</a>
            </div>
            <div class="tabla_contenido">
                <div class="titulo">
                    <h1>Usuarios</h1>
                </div>
                <div class="scroll">
                    <table class="tabla">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Empleado</th>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Password Hash</th>
                                <th>Estado</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listaUsuarios as $datos){ ?>
                            <tr>
                                <td><?php echo($datos["usuario_id"]); ?></td>
                                <td><?php echo($datos["empleado_nombre"] . " " . $datos["empleado_apellido"]); ?></td>
                                <td><?php echo($datos["usuario"]); ?></td>
                                <td><?php echo($datos["email"]); ?></td>
                                <td><?php echo($datos["pasword_hash"]); ?></td>
                                <td><?php echo($datos["estado"]); ?></td>
                                <td><?php echo($datos["rol"]); ?></td>
                                <td>
                                    <form action="../modificar_datos/frmModificarUsuario.php" method="get">
                                        <input type="hidden" name="txt_usuario_id" value="<?php echo($datos['usuario_id']); ?>">
                                        <input type="hidden" name="txt_empleado_nombre" value="<?php echo($datos['empleado_nombre']); ?>">
                                        <input type="hidden" name="txt_empleado_apellido" value="<?php echo($datos['empleado_apellido']); ?>">
                                        <input type="hidden" name="txt_usuario" value="<?php echo($datos['usuario']); ?>">
                                        <input type="hidden" name="txt_email" value="<?php echo($datos['email']); ?>">
                                        <input type="hidden" name="txt_pasword_hash" value="<?php echo($datos['pasword_hash']); ?>">
                                        <input type="hidden" name="txt_estado" value="<?php echo($datos['estado']); ?>">
                                        <input type="hidden" name="txt_rol_id" value="<?php echo($datos['rol_id']); ?>">
                                        <button type="submit">Editar</button>
                                    </form>
                                    <form action="../../back/objetos/eliminar/eliminarUsuario.php" method="post" onsubmit="return confirm('Seguro que desea eliminar este usuario?');">
                                        <input type="hidden" name="txt_usuario_id" value="<?php echo($datos['usuario_id']); ?>">
                                        <button type="submit">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div>
                    <button>
                        <a href="../agregar_datos/frmAgregarUsuario.php">Agregar Usuario</a>
                    </button>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
