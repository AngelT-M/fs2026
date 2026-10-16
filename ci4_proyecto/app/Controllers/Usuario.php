<?php

namespace App\controllers;

use App\Models\UsuarioModel;

class Usuario extends BaseController
{
    public function usua(): string 
    {
        $usuario = new UsuarioModel();
        $datos['datos'] = $usuario->findAll();

        return view('usua',$datos);
    }
}
