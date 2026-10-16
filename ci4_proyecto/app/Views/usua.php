<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

</head>
<body>
    <header>
        <h1>Usuarios</h1>
    </header>
    <main>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Constraseña</th>
                    <th>rol</th>
                    <th>estado</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ($datos as $usuario) {
                ?>
            
                <tr>
                    <td> <?= $usuario['id_usuario'] ?> </td>
                    <td> <?= $usuario['nombre'] ?> </td>
                    <td> <?= $usuario['usuario'] ?> </td>
                    <td> <?= $usuario['correo'] ?> </td>
                    <td> <?= $usuario['contrasena'] ?> </td>
                    <td> <?= $usuario['id_rol'] ?> </td>
                    <td> <?= $usuario['estado'] ?> </td>
                </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </main>





        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>   

</body>
</html>