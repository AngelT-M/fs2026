<?php
namespace App\Controllers;
use App\Models\UsuariosModel;


class UsuariosController extends BaseController
{
    public function mostrarDatos()
    {   
        $model = new UsuariosModel();
        return view('mostrarDatos/usuarios',['datos' => $model->findAll()]);
        
    }

    public function busccar($codigo){
        $model = new UsuariosModel();
        return view('actualizarDatos/actualizarAutor', ['datos' => $model])
    }

}
