<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar region</title>
</head>
<body>
    <header>
        <h1>Agregar Nivel academico </h1>
    </header>
    <main>
        <form action="objetos/agregarNivelAcademico.php" method="post">
            <label for="txt_codigo">Codigo  </label>
            <input type="text" name="txt_codigo" id="txt_codigo">
            <br>
            <label for="txt_nombre">Nombre </label>
            <input type="text" name="txt_nombre" id="txt_nombre">
            <br>
            <label for="txt_descripcion">descripcion</label>
            <input type="text" name="txt_descripcion" id="txt_descripcion">
            <br>
            <br>
            <button type="submit" id="" name="" >Agregar region</button>

        </form>
    </main>


    
</body>
</html>