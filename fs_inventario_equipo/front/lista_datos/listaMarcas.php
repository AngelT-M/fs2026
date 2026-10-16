
<?php
    include_once("../../back/conexion/conexion.php");
    include_once("../../back/clases/Marcas.php");

    $listaMarcas = Marcas::datosMarcas($conexion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Marcas</title>
    <link rel="stylesheet" href="../css/front.css">
    <link rel="stylesheet" href="../css/estilotabla.css">
</head>
<body>
    <header>
            <h1>Datos Marcas</h1>
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

    <div>
            <div>
                <a href="../index.php">Regresar</a>
            </div>
            <div class="tabla_contenido">
                <div class="titulo">
                    <h1>Marcas  </h1>
                </div>
                <div class="scroll">
                <table class="tabla  ">
                    <thead>
                        <tr>
                            <th>Marca</th>
                            <th>Acciones </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  foreach ($listaMarcas as $datos){ ?>
                        <tr>
                           
                            <td><?php echo($datos["marca"]); ?></td>
                            <td>
                                <form action="../modificar_datos/frmModificarMarca.php" method="get">
                                <input type="hidden" name="txt_marca_id" value="<?php echo($datos['marca_id']); ?>">
                                <input type="hidden" name="txt_marca" value="<?php echo($datos['marca']); ?>">
                                <button type="submit">editar</button>
                                </form>
                                <form action="../../back/objetos/eliminar/eliminarMarca.php" method="post"
                                onsubmit="return confirm('Seguro que desea eliminar esta region?');">
                                
                                    <input type="hidden" name="txt_marca_id" value="<?php echo($datos['marca_id']); ?>" >
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
                        <a href="../agregar_datos/frmAgregarMarca.php">Agregar Marca</a>
                    </button>
                </div>
            </div>
        </div>

    
</body>
</html>
