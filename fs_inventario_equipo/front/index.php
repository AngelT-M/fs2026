<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datos Inventario Equipos</title>
    <link rel="stylesheet" href="css/front.css">
</head>
<body>
    <header>
        <h1>Datos Inventario Equipos</h1>
    </header>
    <nav>
        <div class="barra">
            <div class="barra_logo">
                <div>
                    <a href="index.php">Inicio</a>
                </div>
            </div>
            <div class="barra_link">
                <div><a href="lista_datos/listaEquipos.php">Equipos</a></div>
                <div><a href="lista_datos/listaMarcas.php">Marcas</a></div>
                <div><a href="lista_datos/listaTipos.php">Tipos</a></div>
                <div><a href="lista_datos/listaEmpleados.php">Empleados</a></div>
                <div><a href="lista_datos/listaPuestos.php">Puestos</a></div>
                <div><a href="lista_datos/listaUsuarios.php">Usuarios</a></div>
                <div><a href="lista_datos/listaRoles.php">Roles</a></div>
            </div>
        </div>
    </nav>
    <main>
        <section class="home_hero">
            <div class="home_meta">
                <span class="home_chip">Inventario</span>
                <span class="home_chip">Administracion simple</span>
                <span class="home_chip">Acceso rapido</span>
            </div>
            <h2>Bienvenido al sistema de inventario</h2>
            <p>
                Desde aqui puedes entrar a los modulos principales para administrar equipos,
                empleados, puestos, marcas, tipos, roles y usuarios.
            </p>
            <div class="home_grid">
                <a class="home_card" href="lista_datos/listaEquipos.php">
                    <h3>Equipos</h3>
                    <p>Ver, agregar, editar y eliminar equipos.</p>
                </a>
                <a class="home_card" href="lista_datos/listaEmpleados.php">
                    <h3>Empleados</h3>
                    <p>Administrar el personal del inventario.</p>
                </a>
                <a class="home_card" href="lista_datos/listaUsuarios.php">
                    <h3>Usuarios</h3>
                    <p>Gestionar usuarios vinculados a empleados.</p>
                </a>
                <a class="home_card" href="lista_datos/listaMarcas.php">
                    <h3>Marcas</h3>
                    <p>Organizar las marcas de los equipos.</p>
                </a>
                <a class="home_card" href="lista_datos/listaTipos.php">
                    <h3>Tipos</h3>
                    <p>Clasificar cada tipo de equipo.</p>
                </a>
                <a class="home_card" href="lista_datos/listaRoles.php">
                    <h3>Roles</h3>
                    <p>Controlar permisos y perfiles de acceso.</p>
                </a>
            </div>
        </section>
    </main>
</body>
</html>
