<?php

namespace App\Controllers;

use App\Models\ProyectoModel;
use App\Models\UsuarioModel;

class Proyecto extends BaseController
{
    public function pro(): string
    {
        $proyecto = new ProyectoModel();
        $usuario = new UsuarioModel();

        $datos['datos'] = $proyecto->findAll();
        $datos['usuario']=$usuario->findAll();

        return view('pro',$datos);
    }
}