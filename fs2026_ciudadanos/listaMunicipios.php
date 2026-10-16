<?php
    require_once "conexion/conexion.php";
  
    require_once "clases/Municipios.php";
    

    $listaMunicipios = Municipios::obtenerMuni($conexion);
   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Municipios</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Municipios </h1>
    </header>
    <button>
        <a href="frmAgregar_municipio.php">Agregar Departamento</a>
    </button>
    <div>
        <table>
            <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Departamento</th>
                    <th>Regiones</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($listaMunicipios as $lista){ 
                ?>
                <tr>
                    <td> <?=$lista["cod_muni"];?> </td>
                    <td> <?=$lista["nombre_municipio"];?> </td>
                    <td> <?=$lista["nombre_depto"];?> </td>
                    <td> <?=$lista["nombre_region"];?> </td>

                    <td>
                        <form action="frmModificar_municipio.php" method="get">
                            <input type="hidden" name="txt_codigo" value="<?php echo($lista["cod_muni"]);?>">
                            <input type="hidden" name="txt_nombre" value="<?php echo($lista["nombre_municipio"]);?>">
                            <input type="hidden" name="txt_departamento" value="<?php echo($lista["nombre_depto"]);?>">
                            <button type="submit" name="btn_editar">Editar</button>
                        </form>
                    </td>
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
        
    </div>
</body>
</html>