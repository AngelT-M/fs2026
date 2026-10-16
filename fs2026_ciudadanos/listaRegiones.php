<?php
    // utilizar el archivo de conexion y las clases
    include_once("conexion/conexion.php");
    include_once("clases/Region.php");
    include_once("clases/NivelAcademico.php");

    $listaRegiones = Region::obtenerRegiones($conexion);
    $listaAcademica = NivelAcademico::obtenerNivelAcademico($conexion);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Regiones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="bg-light">
    <main class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h1 class="h3 mb-1">Regiones</h1>
                <p class="text-muted mb-0">Listado general de regiones registradas en el sistema.</p>
            </div>
            <a class="btn btn-primary" href="frmAgregar_region.php">Agregar region</a>
        </div>

        <div class="card border-0 shadow-sm mb-5">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Descripcion</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listaRegiones as $datos){ ?>
                            <tr>
                                <td><?php echo($datos["cod_region"]); ?></td>
                                <td><?php echo($datos["nombre"]); ?></td>
                                <td><?php echo($datos["descripcion"]); ?></td>
                                <td>
                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        <form action="frmModificar_region.php" method="get" class="m-0">
                                            <input type="hidden" name="txt_cod" value="<?php echo($datos["cod_region"]);?>">
                                            <input type="hidden" name="txt_nombre" value="<?php echo($datos["nombre"]);?>">
                                            <input type="hidden" name="txt_descripcion" value="<?php echo($datos["descripcion"]); ?>">
                                            <button type="submit" name="btn_editar" class="btn btn-sm btn-outline-primary">Editar</button>
                                        </form>
                                        
                                        <form action="objetos/eliminarRegion.php" method="post" class="m-0" onsubmit="return confirm('Seguro que deseas eliminar esta region?');">
                                            <input type="hidden" name="txt_codigo" value="<?php echo($datos["cod_region"]);?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h2 class="h4 mb-1">Niveles Academicos</h2>
                <p class="text-muted mb-0">Vista secundaria con el mismo estilo sencillo.</p>
            </div>
            <a class="btn btn-success" href="frmAgregar_niveles_academicos.php">Agregar nivel</a>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Descripcion</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listaAcademica as $datos){ ?>
                            <tr>
                                <td><?php echo($datos["cod_nivel_acad"]); ?></td>
                                <td><?php echo($datos["nombre"]); ?></td>
                                <td><?php echo($datos["descripcion"]); ?></td>
                                <td class="text-center">
                                    <form action="frmModificar_nivelAcademico.php" method="get" class="m-0">
                                        <input type="hidden" name="txt_nivel" value="<?php echo($datos['cod_nivel_acad']);?>">
                                        <input type="hidden" name="txt_nombre" value="<?php echo($datos['nombre']);?>">
                                        <input type="hidden" name="txt_descripcion" value="<?php echo($datos['descripcion']);?>">
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Editar</button>
                                    </form>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
