<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Planes extends ResourceController
{
    protected $modelName = "App\Models\PlanesModel";
    protected $format ="json";
    
    public function index()
    {
        //método GET muestra todos los registros
        
        //this hace referencia al $modelName
        $planes = $this->model->findAll();
        //retorna todos los productos encontrados en formato indicado en $format
        return $this->respond($planes);

    }

  
    public function show($id = null)
    {
        //
        $planes=$this->model->find($id);
        if($planes){
            return $this->respond($planes);
        }
        return $this->failNotFound("Producto no encontrado");
    }

    public function new()
    {
        //no se necesita, se utiiza para mostrar el formulario para insertar
    }

    public function create()
    {
        //
        $data=$this->request->getJSON(true);

        if($this->model->insert($data)){
            return $this->respondCreated($data,"Datos almacenados");
        }
        return $this->failValidationErrors($this->model->errors());

    }

    public function update($id = null)
    {
        //
        $planes=$this->model->find($id);
        if(!$planes){
            return $this->failNotFound('Producto no encontrado');
        }
        $data=$this->request->getJSON(true);
        //print_r($data);
        if($this->model->update($id,$data)){
            return $this->respondUpdated($data,"Datos actualizados");
        }
        //return $this->fail("no se puede actualizar");
        return $this->failValidationErrors($this->model->errors());

    }

    public function delete($id = null)
    {
        //
        $planes=$this->model->find($id);
        if($planes){
            $this->model->delete($id);
            return $this->respondDeleted($planes,'Producto no encontrado');
        }
        return $this->failNotFound('Producto no encontrado');
    }
}
