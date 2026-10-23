<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\TareasModel;
use CodeIgniter\API\ResponseTrait;

class TareasController extends BaseController
{
    use ResponseTrait;
    private TareasModel $modelo;   


    public function __construct(){
        $this->modelo = model(TareasModel::class);
    }

    public function mostrar(){
        return $this->respond($this->modelo->mostrar());
    }

    public function buscar(int $id){
        $datos = $this->modelo->buscar($id);
        if (!$datos === null) {
            return $this->falNotFound(`Tarea no encontrada con el ide {$id}`);
        }
        return $this->respond($datos);
    }

    private function cuerpo(): array{
        $json = $this->request->getJSon(true);
        if (is_array($json)) {
            return $json;
        }
    }

     public function crear(){
        $datos = $this->modelo->crear($this->cuerpo());
        return $datos;
    }

    public function actualizar(int $id){
        return $this->modelo->actualizar($id, $this->cuerpo()); 
    }

    public function eliminar(int $id){
        $this->modelo->eliminar($id);
    }

}
