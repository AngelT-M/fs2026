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
        <h1>Persona </h1>
    </header>
    <main>
        <form action="objetos/objetoPersona.php" method="post">
            <label for="txt_nombre">Nombre</label>
            <input type="text" name="txt_nombre" id="txt_nombre">

            <label for="txt_fecha">Fecha de nacimiento</label>
            <input type="date" name="txt_fecha" id="txt_fecha">

            <button type="submit" name="btn_enviar" id="btn_enviar" >Enviar datos</button>
        
        </form>
    </main>
    
</body>
</html>