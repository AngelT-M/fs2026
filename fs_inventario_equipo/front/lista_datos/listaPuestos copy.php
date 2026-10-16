<?php
    include_once("../../back/conexion/conexion.php");
    include_once("../../back/clases/Puestos.php");

    $listaPuestos = Puestos::datosPuestos($conexion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/front.css">
    <link rel="stylesheet" href="../css/estilotabla.css">
</head>
<body>
    <header>
        <h1>
            Datos Puestos 
        </h1>
    </header>
    <nav>
        <div class="barra">
            <div class="barra_logo">
                <div >
                    <a href="">Logotipo</a>
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
                    <h1>Puestos  </h1>
                </div>
                <div class="scroll">
                <table class="tabla  ">
                    <thead>
                        <tr>
                            <th>Puesto</th>
                            <th>Acciones </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  foreach ($listaPuestos as $datos){ ?>
                        <tr>
                           
                            <td><?php echo($datos["puesto"]); ?></td>
                            <td>
                                <form action="../modificar_datos/frmModificarPuesto.php" method="get">
                                <input type="hidden" name="txt_puesto_id" value="<?php echo($datos['puesto_id']); ?>">
                                <input type="hidden" name="txt_puesto" value="<?php echo($datos['puesto']); ?>">
                                <button type="submit">editar</button>
                                </form>
                                <form action="../../back/objetos/eliminar/eliminarPuesto.php" method="post"
                                onsubmit="return confirm('Seguro que desea eliminar esta region?');">
                                
                                    <input type="hidden" name="txt_puesto_id" value="<?php echo($datos['puesto_id']); ?>" >
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
                        <a href="../agregar_datos/frmAgregarPuesto.php">Agregar Puesto</a>
                    </button>
                </div>
            </div>
        </div>

    
</body>
</html>