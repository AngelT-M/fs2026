<?php
    require_once("../../conexion/conexion.php");
    require_once("../../clases/Usuarios.php");

    $usuario = new Usuarios;

    $usuario->setUsuarioId($_GET["txt_usuario_id"]);
    $usuario->setUsuario($_GET["txt_usuario"]);
    $usuario->setEmail($_GET["txt_email"]);
    $usuario->setPaswordHash($_GET["txt_pasword_hash"]);
    $usuario->setEstado($_GET["txt_estado"]);
    $usuario->setRolId($_GET["lst_rol"]);

    $usuario->setConexion($conexion);

    if ($info = $usuario->modificarUsuario()){
        echo "Datos Guardados";
    }
    else {
        echo "Erro";
    }
?>
<br>
<br>
<button>
    <a href="../../../front/lista_datos/listaUsuarios.php">Regresar</a>
</button>
