<?php

namespace App\Controllers;

use App\Models\TokensModel;
use CodeIgniter\API\ResponseTrait;

class TokensController extends BaseController
{
    use ResponseTrait;

    private TokensModel $modelo;   

    public function __construct(){
        $this->modelo = model(TokensModel::class);
    }

    public function mostrar(){
        return $this->respond($this->modelo->mostrar());
    }

    public function buscar(int $id){
        $datos = $this->modelo->buscar($id);
        if ($datos === null) {
            return $this->failNotFound("Token no encontrado con el id {$id}");
        }
        return $this->respond($datos);
    }

    private function cuerpo(): array{
        $json = $this->request->getJSON(true);
        return is_array($json) ? $json : [];
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
