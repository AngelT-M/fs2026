<?php
    include_once("../../back/conexion/conexion.php");
    include_once("../../back/clases/Equipos.php");

    $listaEquipos = Equipos::datosEquipos($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Equipos</title>
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
                    <h1>Equipos</h1>
                </div>
                <div class="scroll">
                    <table class="tabla">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Num. de serie</th>
                                <th>Marca</th>
                                <th>Descripcion</th>
                                <th>Fecha compra</th>
                                <th>Precio</th>
                                <th>Tipo</th>
                                <th>Encargado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listaEquipos as $datos){ ?>
                            <tr>
                                <td><?php echo($datos["equipo_id"]); ?></td>
                                <td><?php echo($datos["no_serie"]); ?></td>
                                <td><?php echo($datos["marca"]); ?></td>
                                <td><?php echo($datos["descripcion"]); ?></td>
                                <td><?php echo($datos["fecha_compra"]); ?></td>
                                <td><?php echo($datos["precio"]); ?></td>
                                <td><?php echo($datos["tipo"]); ?></td>
                                <td><?php echo($datos["empleado"]); ?></td>
                                <td>
                                    <form action="../modificar_datos/frmModificarEquipo.php" method="get">
                                        <input type="hidden" name="txt_equipo" value="<?php echo($datos['equipo_id']); ?>">
                                        <input type="hidden" name="txt_serie" value="<?php echo($datos['no_serie']); ?>">
                                        <input type="hidden" name="txt_marca" value="<?php echo($datos['marca_id']); ?>">
                                        <input type="hidden" name="txt_descripcion" value="<?php echo($datos['descripcion']); ?>">
                                        <input type="hidden" name="txt_fecha" value="<?php echo($datos['fecha_compra']); ?>">
                                        <input type="hidden" name="txt_precio" value="<?php echo($datos['precio']); ?>">
                                        <input type="hidden" name="txt_tipo" value="<?php echo($datos['tipo_id']); ?>">
                                        <input type="hidden" name="txt_empleado" value="<?php echo($datos['empleado_id']); ?>">
                                        <button type="submit">Editar</button>
                                    </form>
                                    <form action="../../back/objetos/eliminar/eliminarEquipo.php" method="post" onsubmit="return confirm('Seguro que desea eliminar este equipo?');">
                                        <input type="hidden" name="txt_equipo" value="<?php echo($datos['equipo_id']); ?>">
                                        <button type="submit">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="page_actions">
                    <a class="btn" href="../agregar_datos/frmAgregarEquipo.php">Agregar Equipo</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
