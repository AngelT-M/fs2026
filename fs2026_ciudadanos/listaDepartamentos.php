<?php
    include_once("conexion/conexion.php");
    include_once("clases/Departamento.php");
    include_once("clases/Municipios.php");

    //objeto para llamar los metodos 
    $listaDepto = Departamento::obtenerDepartamentos($conexion);

    $listaMuni = Municipios::obtenerMuni($conexion);

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Departamentos </title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Departamentos </h1>
    </header>
    <main>
        <button>
        <a href="frmAgregar_departamento.php">Agregar Departamento</a>
        </button>
        <br>
        <table >
            <thead>
                <tr>
                    <th>Codigo</th>
                    <th>Nombre</th>
                    <th>Region</th>
                    <th>Acciones</th>
                </tr>
            </thead> 
  
            <tbody>
                <?php
                    foreach ($listaDepto as $datos){
                ?>
                <tr>
                    <td> 
                        <?php echo($datos["cod_depto"]); ?>
                    </td>
                    <td>
                        <?php echo($datos["nombre_depto"]); ?>
                    </td>
                    <td>
                        <?php echo($datos["nombre"]); ?>
                    </td>

                    <td>
                        <form action="frmModificar_departamento.php" method="get">
                            <input type="hidden" name="txt_codigo" value="<?php echo($datos['cod_depto']); ?>">
                            <input type="hidden" name="txt_nombre" value="<?php echo($datos['nombre_depto']); ?>">
                            <input type="hidden" name="txt_region" value="<?php echo($datos['nombre']); ?>">
                            <button type="summit" name="btn_editar_depto">Editar</button>
                        </form>
                        
                        <button type="submit">Eliminar</button>
                    </td>
                </tr>
                <?php
                }
                ?>

            </tbody>
        </table>



        
        <h1>Municipios</h1>

        <table>
            <thead>
                <tr>
                    <th>Codigo </th>
                    <th>Nombre </th>
                    <th>Departamento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($listaMuni as $datos){ 
                ?>
                <tr>
                    <td> <?php echo ($datos["cod_muni"]) ?> </td>
                    <td> <?php echo ($datos["nombre_municipio"]) ?>  </td>
                    <td> <?php echo ($datos["nombre_depto"]) ?>  </td>
                    <td>
                        <button type="submit">Actualizar</button>
                        <button type="submit">Eliminar</button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
    
</body>
</html>