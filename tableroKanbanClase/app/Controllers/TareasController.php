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
        if ($datos === null) {
            return $this->failNotFound(`La tareas que busca no se encuentra, ID {$id}`);
        }
        return $this->respond($datos);
    }

    private function cuerpo(): array{
        $json = $this->request->getJSON(true);
        if (is_array($json)) {
            return $json;
        }
    }

    public function crear(){
        $id = $this->modelo->crear($this->cuerpo()); 
        return $id;
    }

    public function actualizar(int $id){
        return $this->modelo->actualizar($id, $this->cuerpo());
    }

    public function eliminar(int $id){
        $this->modelo->eliminar($id); 
    }



}
