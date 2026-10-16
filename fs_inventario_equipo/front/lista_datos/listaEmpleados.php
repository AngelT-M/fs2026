<?php
    include_once("../../back/conexion/conexion.php");
    include_once("../../back/clases/Empleados.php");

    $listaEmpleados = Empleados::mostrarEmpleados($conexion);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Empleados</title>
    <link rel="stylesheet" href="../css/front.css">
    <link rel="stylesheet" href="../css/estilotabla.css">
</head>
<body>
    <header>
            <h1>Datos Inventario Equipos</h1>
    </header>
    <nav>
        <div class="barra">
            <div class="barra_logo">
                <div >
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
                    <h1>Empleados </h1>
                </div>
                <div class="scroll">
                <table class="tabla  ">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Telefono</th>
                            <th>Puesto</th>
                            <th>fecha nacimiento</th>
                            <th>Accione </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  foreach ($listaEmpleados as $datos){ ?>
                        <tr>
                            <td><?php echo($datos["nombre"]); ?></td>
                            <td><?php echo($datos["apellido"]); ?></td>
                            <td><?php echo($datos["telefono"]); ?></td>
                            <td><?php echo($datos["puesto"]); ?></td>
                            <td><?php echo($datos["fecha_nacimiento"]); ?></td>
                            <td>
                                <form action="../modificar_datos/frmModificarEmpleado.php" method="get">
                                <input type="hidden" name="txt_empleado" value="<?php echo($datos['empleado_id']); ?>">
                                <input type="hidden" name="txt_nombre" value="<?php echo($datos['nombre']); ?>">
                                <input type="hidden" name="txt_apellido" value="<?php echo($datos['apellido']); ?>">
                                <input type="hidden" name="txt_telefono" value="<?php echo($datos['telefono']); ?>">
                                <input type="hidden" name="txt_puesto" value="<?php echo($datos['puesto']); ?>">
                                <input type="hidden" name="txt_fecha" value="<?php echo($datos['fecha_nacimiento']); ?>">
                                <button type="submit">editar</button>
                                </form>
                                <form action="../../back/objetos/eliminar/eliminarEmpleado.php" method="post" onsubmit="return confirm('Seguro que desea eliminar este empleado?');">
                                    <input type="hidden" name="txt_empleado" value="<?php echo($datos['empleado_id']); ?>">
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
                        <a href="../agregar_datos/frmAgregarEmpleado.php">Agregar Empleado</a>
                    </button>
                </div>
            </div>
        </div>
    </main>


    
</body>
</html>
