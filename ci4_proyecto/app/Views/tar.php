<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>
<body>
     <h3>Datos </h3>
    <form action=" <?=base_url('guardar_tarea')  ?>" method="post" class="container-fluid">
       
        <label for="txt_codigo">ID</label>
        <input type="number" name="txt_codigo" id="txt_codigo">
        <br>
        <label for="txt_titulo">Titulo</label>
        <input type="text" name="txt_titulo" id="txt_titulo">
        <br>
        <label for="txt_descripcion">Descripcion</label>
        <input type="text" name="txt_descripcion" id="txt_descripcion">
        <br>
        
        <label for="txt_proyecto">Proyecto</label>
        <select name="txt_proyecto" id="txt_proyecto">

            <?php foreach($proyectos as $pro):?>
            <option value=" <?=$pro['id_proyecto']?>">
                <?= $pro['nombre'] ?>
            </option>
            <?php endforeach;  ?>

        </select>
        <br>
        <label for="txt_categoria">Categoria</label>
        <select name="txt_categoria" id="txt_categoria" class="">

            <?php foreach ($categorias as $categoria):?>
            <option value=" <?= $categoria['id_categoria'] ?> ">
                <?= $categoria['nombre'] ?>
            </option>
            <?php endforeach;?>

        </select>
        <br>
        <label for="txt_prioridad">Prioridad</label>
        <select name="txt_prioridad" id="txt_prioridad">
            <option value="PENDIENTE">Pendiente</option>
            <option value="EN_PROCESO">En proceso</option>
            <option value="COMPLETADO">Completado</option>
        </select>
        <br>
        <label for="txt_fecha">Fecha limite</label>
        <input type="date" name="txt_fecha" id="txt_fecha">
        <br>
        <button type="submit">Guardar</button>

    </form>


    <div class="container-fluid">
        <h1>Cartas</h1>

        <div class="row">
            <?php
            foreach ($datos as $tarea) {
            ?>
            <div class="col ">
                <div class="card " style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title"><?= $tarea['id_tarea'] ?></h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary"><?= $tarea['titulo']?></h6>
                        <p class="card-text"><?= $tarea['descripcion'] ?></p>
                        <p class="card-text"><?= $tarea['id_proyecto'] ?></p>
                        <p class="card-text"><?= $tarea['id_categoria'] ?></p>
                        <p class="card-text"><?= $tarea['prioridad'] ?></p>
                        <p class="card-text"><?= $tarea['fecha_limite'] ?></p>
                        <a href="#" class="card-link">actualizar</a>
                        <a href="#" class="card-link">Eliminar</a>
                    </div>
                </div>
            </div>
            <?php
            
            }
            ?>


        </div>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>   
</body>
</html>