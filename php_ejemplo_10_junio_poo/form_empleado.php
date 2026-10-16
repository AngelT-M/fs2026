<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Docum
        ent</title>
</head>
<body>
    <header>
        <h1>Empleado </h1>
    </header>
    <main>
        <form action="objetos/objetoEmpleado.php" method="post">
            <label for="txt_nombre">Nombre</label>
            <input type="text" name="txt_nombre" id="txt_nombre">
            <br>
            <label for="txt_fecha">Fecha de nacimiento</label>
            <input type="date" name="txt_fecha" id="txt_fecha">
            <br>

            <label for="txt_codigo">Codigo</label>
            <input type="number" name="txt_codigo" id="txt_codigo">
            <br>
            <label for="txt_puesto">Puesto</label>
            <input type="text" name="txt_puesto" id="txt_puesto">
            <br>
            <label for="date_fecha_contratacion">Fecha de contratacion</label>
            <input type="date" name="date_fecha_contratacion" id="date_fecha_contratacion">
            <br>
            <label for="txt_salario">Salario</label>
            <input type="number" name="txt_salario" id="txt_salario">
            <br>


            <button type="submit" name="btn_enviar" id="btn_enviar" >Enviar datos</button>
        
        </form>
    </main>
    
</body>
</html>