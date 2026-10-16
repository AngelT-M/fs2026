<?php
    include_once("conexion/conexion.php");
    include_once("clases/Ciudadanos.php");

    $listaCiudadanos = Ciudadanos::obtenerCiudadanos($conexion);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista ciudadanos</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Datos Ciudaddanos </h1>
    </header>
    <main>
        <button>
        <a href="frmAgregar_ciudadano.php">Agregar Departamento</a>
        </button>

        <table>
            <thead>
                <tr>
                    <th>DPI</th>
                    <th>Apellido</th>
                    <th>Nombre</th>
                    <th>Direccion</th>
                    <th>telefono casa</th>
                    <th>telefono movil</th>
                    <th>Email</th>
                    <th>Fecha Nacimiento </th>
                    <th>nivel academico </th>
                    <th>municipio</th>
                    <th>contraseña</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listaCiudadanos as $datos){   ?>
                <tr>
                    <td> <?php echo($datos["dpi"]); ?> </td>
                    <td> <?php echo($datos["apellido"]); ?></td>
                    <td> <?php echo($datos["nombre"]); ?></td>
                    <td> <?php echo($datos["direccion"]); ?></td>
                    <td> <?php echo($datos["tel_casa"]); ?></td>
                    <td> <?php echo($datos["tel_movil"]); ?></td>
                    <td> <?php echo($datos["email"]); ?></td>
                    <td> <?php echo($datos["fechanac"]); ?></td>
                    <td> <?php echo($datos["nivel"]); ?></td>
                    <td> <?php echo($datos["municipio"]); ?></td>
                    <td> <?php echo($datos["contra"]); ?></td>
                    <td>
                        <form action="frmModificar_ciudadano.php" method="get">
                        <input type="hidden" name="txt_dpi" value="<?php echo($datos['dpi']);?>">
                        <input type="hidden" name="txt_apellido" value="<?php echo($datos['apellido']);?>">
                        <input type="hidden" name="txt_nombre" value="<?php  echo($datos['nombre']);?>">
                        <input type="hidden" name="txt_direccion" value="<?php echo($datos['direccion']);?>">
                        <input type="hidden" name="txt_telCasa" value="<?php echo($datos['tel_casa']);?>">
                        <input type="hidden" name="txt_telMovil" value="<?php  echo($datos['tel_movil']);?>">
                        <input type="hidden" name="txt_email" value="<?php echo($datos['email']);?>">
                        <input type="hidden" name="txt_fechanac" value="<?php echo($datos['fechanac']);?>">
                        <input type="hidden" name="txt_nivel" value="<?php  echo($datos['nivel']);?>">
                        <input type="hidden" name="txt_municipio" value="<?php echo($datos['municipio']);?>">
                        <input type="hidden" name="txt_contra" value="<?php echo($datos['contra']);?>"> 
                        <button type="submit">Editar</button>

                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

    </main>

    
</body>
</html>