<?php

namespace App\Controllers;
use App\Models\CategoriasModel;
class Categorias extends BaseController
{
    public function cat(): string
    {
        $categoria = new CategoriasModel();

        $datos['datos'] = $categoria->findAll();

        return view('cat',$datos);
    }
    public function guardar()
    {
        $categoria = new CategoriasModel();
        
        $codigo = $this->request->getVar('txt_codigo');
        $nombre = $this->request->getVar('txt_nombre');
        $descripcion = $this->request->getVar('txt_descripcion');
        $estado = $this->request->getVar('txt_estado');

        $datos=[
            'id_categoria' =>$codigo,
            'nombre' =>$nombre,
           'descripcion' =>$descripcion,
            'estado' =>$estado
        ];
        $categoria->insert($datos);

        return $this->cat();
    }
}