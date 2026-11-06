<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\CategoriaModel;
use CodeIgniter\API\ResponseTrait;

class CategoriaController extends BaseController
{
    use ResponseTrait;

    private CategoriaModel $modelo;

    public function __construct(){
        $this->modelo = model(CategoriaModel::class);
    }

    public function mostrar(){
        return $this->respond($this->modelo->mostrar());
    }

    public function buscar(int $id){
        $dato = $this->modelo->buscar($id);
        if ($dato === null ) {
            return $this->failNotFound(`El iD que estas buscaco no fue encontrado`);
        }
        return $this->response($dato);
    }

    public function cuerpo(){
        $json = $this->request->getJSOn(true);
        if (is_array($json)) {
            return $json;
        }
    }

    public function crear(){
        $datos = $this->modelo->crear($this->cuerpo());
        return $dato;
    } 

    public function actualizar(int $id){
        return $this->modelo->actualizar($id, $this->cuerpo());
    }

    public function elimina(int $id){
        $this->modelo->eliminar($id);
    }



}
