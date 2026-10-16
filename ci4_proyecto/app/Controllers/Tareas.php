<?php

namespace App\Controllers;
use App\Models\TareasModel;
use App\Models\CategoriasModel;
use App\Models\ProyectoModel;

class Tareas extends BaseController
{
    public function tar(): string
    {
        $tarea = new TareasModel();
        $categorias = new CategoriasModel;
        $proyectos = new ProyectoModel;

        $datos['datos'] = $tarea->findAll();
        $datos['categorias'] = $categorias->findAll();
        $datos['proyectos'] = $proyectos->findAll();

        return view('tar',$datos);
    }
    public function guardar()
    {
        $tarea = new TareasModel();

        $codigo = $this->request->getVar('txt_codigo');
        $titulo = $this->request->getVar('txt_titulo');
        $descripcion = $this->request->getVar('txt_descripcion');
        $proyecto = $this->request->getVar('txt_proyecto');
        $categoria = $this->request->getVar('txt_categoria');
        $prioridad = $this->request->getvar('txt_prioridad');
        $fecha = $this->request->getVar('txt_fecha');

        $datos=[
        'id_tarea' => $codigo,
        'titulo' => $titulo,
        'descripcion' => $descripcion,
        'id_proyecto' => $proyecto,
        'id_categoria' => $categoria,
        'prioridad' => $prioridad,
        'fecha_limite' => $fecha
        ];
        $tarea->insert($datos);
        return $this->tar();
        
    }
}