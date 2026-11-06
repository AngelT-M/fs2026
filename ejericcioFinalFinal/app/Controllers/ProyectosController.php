<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\ProyectosModel;
use CodeIgniter\API\ResponseTrait;

class ProyectosController extends BaseController
{
    use ResponseTrait;
    private ProyectosModel $modelo;

    public function __construct(){
        $this->modelo = model(ProyectosModel::class);
    }

    public function mostrar(){
        return $this->respond($this->modelo->mostrar());
    }

    public function buscar(int $id){
        $datos = $this->modelo->buscar($id);
        return $this->respond($datos);
    }

    private function cuerpo(){
        $json = $this->request->getJSON(true);
        if (is_array($json)) {
            return $json;
        }
        return [];
    }

    public function crear(){
        $valor = $this->modelo->crear($this->cuerpo());
        if (! $valor) {
            return $this->failNotFound('No se logro crear el proyecto');
        }
        return $valor;
    }

    public function actualizar(int $id){
        $valor = $this->modelo->actualizar($id, $this->cuerpo());
        return $valor;
    }

    public function eliminar($id){
        $del = $this->modelo->eliminar($id);
    }



}
