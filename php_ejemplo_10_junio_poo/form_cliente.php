<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
    <header class="py-4">
        <div class="container">
            <div class="text-center">
                <h1 class="fw-bold mb-1">Cliente </h1>
                <p class="text-muted mb-0">Completa el formulario con los datos solicitados</p>
            </div>
        </div>
    </header>

    <main class="pb-5 flex-grow-1">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8 col-xl-7">
                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4 p-md-5">
                            <form action="objetos/objetoCliente.php" method="post" class="needs-validation" novalidate>
                                <div class="mb-3">
                                    <label for="txt_nombre" class="form-label">Nombre</label>
                                    <input type="text" name="txt_nombre" id="txt_nombre" class="form-control form-control-lg" placeholder="Ingrese el nombre">
                                </div>

                                <div class="mb-3">
                                    <label for="txt_fecha" class="form-label">Fecha de nacimiento</label>
                                    <input type="date" name="txt_fecha" id="txt_fecha" class="form-control form-control-lg">
                                </div>

                                <div class="mb-3">
                                    <label for="txt_codigo" class="form-label">Codigo</label>
                                    <input type="number" name="txt_codigo" id="txt_codigo" class="form-control form-control-lg" placeholder="Ingrese el codigo">
                                </div>

                                <div class="mb-3">
                                    <label for="txt_clasificacion" class="form-label">Clasificacion</label>
                                    <input type="text" name="txt_clasificacion" id="txt_clasificacion" class="form-control form-control-lg" placeholder="Ingrese la clasificacion">
                                </div>

                                <div class="mb-3">
                                    <label for="date_fecha_registro" class="form-label">Fecha de Registro</label>
                                    <input type="date" name="date_fecha_registro" id="date_fecha_registro" class="form-control form-control-lg">
                                </div>

                                <div class="mb-4">
                                    <label for="txt_monto_credito" class="form-label">Monto de Credito</label>
                                    <input type="number" name="txt_monto_credito" id="txt_monto_credito" class="form-control form-control-lg" placeholder="Monto de cretido">
                                </div>

                                <button type="submit" name="btn_enviar" id="btn_enviar" class="btn btn-primary btn-lg w-100">
                                    Enviar datos
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
