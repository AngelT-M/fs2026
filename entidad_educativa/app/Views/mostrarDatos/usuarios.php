
<?php helper('url'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('style/style.css') ?>">
     
</head>
<body>
    <div>
        <?= $this->include('layouts/navbar') ?>
    </div>
    <div class="contenido-tabla">
        <div class="tabla">
        <table class="">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Constraseña</th>
                    <th>Rol id</th>
                    <th>acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($datos as $usuario):?>
                <tr class="filas-cont">
                    <td><?= esc($usuario['usuario_id'])?></td>
                    <td><?= esc($usuario['nom_usuario'])?></td>
                    <td><?= esc($usuario['PASSWORD'])?></td>
                    <td><?= esc($usuario['rol_id'])?></td>
                    <td>
                        <a href="" class="btn-primario">actualizar</a>
                        <a href="" class=btn-secundario>Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>