<?php
    include_once("../../back/conexion/conexion.php");
    include_once("../../back/clases/Roles.php");

    $listaRoles = Roles::datosRoles($conexion);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Roles</title>
    <link rel="stylesheet" href="../css/front.css">
    <link rel="stylesheet" href="../css/estilotabla.css">
</head>
<body>
    <header>
        <h1>
            Datos Roles 
        </h1>
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
                    <h1>Roles  </h1>
                </div>
                <div class="scroll">
                <table class="tabla  ">
                    <thead>
                        <tr>
                            <th>Rol </th>
                            <th>Descr</th>
                            <th>Acciones </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php  foreach ($listaRoles as $datos){ ?>
                        <tr>
                            <td><?php echo($datos["nombre"]); ?></td>
                            <td><?php echo($datos["descripcion"]); ?></td>
                            <td>
                                <form action="../modificar_datos/frmModificarRol.php" method="get">
                                <input type="hidden" name="txt_rol" value="<?php echo($datos['rol_id']); ?>">
                                <input type="hidden" name="txt_nombre" value="<?php echo($datos['nombre']); ?>">
                                <input type="hidden" name="txt_descripcion" value="<?php echo($datos['descripcion']); ?>">
                                <button type="submit">editar</button>
                                </form>
                                <form action="../../back/objetos/eliminar/eliminarRol.php" method="post"
                                onsubmit="return confirm('Seguro que desea eliminar esta region?');">
                                
                                    <input type="hidden" name="txt_rol" value="<?php echo($datos['rol_id']); ?>" >
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
                        <a href="../agregar_datos/frmAgregarRol.php">Agregar Rol</a>
                    </button>
                </div>
            </div>
        </div>
    </main>


    
</body>
</html>
