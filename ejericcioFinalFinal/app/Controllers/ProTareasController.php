<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

use App\Models\ProTareasModel;
use CodeIgniter\API\ResponseTrait;

class ProTareasController extends BaseController
{
    use ResponseTrait;
    private ProTareasModel $modelo;

    public function __construct(){
        $this->modelo = model(ProTareasModel::class);
    }

    public function mostrar(int $idProyecto) :ResponseInterface {
        return $this->respond($this->modelo->mostrar($idProyecto));
    }

    public function buscar(int $idProyecto, int $id ):ResponseInterface
    {
        $datos = $this->modelo->buscar($idProyecto, $id);
        if ($datos === null ) {
            return $this->failNotFound('Tarea no encontrada');
        }
        return $this->respond($datos);
    }

    private function cuerpo(): array{
        $json = $this->request->getJSON(true);
        if (is_array($json)) {
            return $json;
        }
        return [];
    }


    public function crear(int $idProyecto): ResponseInterface{
        $valor = $this->modelo->crear($idProyecto, $this->cuerpo());
        if ($valor === null ) {
            return $this->fail('no se puedo crear la tarea ');
        }
        return $this->ResponCreated(['id_tarea' => $valor]);
    }

    public function actualizar(int $idProyecto,int $id): ResponseInterface 
    {
        $valor = $this->modelo->actualizar($idProyecto, $id, $this->cuerpo());
        if ($valor === null) {
            return $this->failNotFoud('NO se puedo actualizar la tarea');
        }
        return $this->respond(['mensaje' => 'Actualizacion eixtosa']);
    }

    public function eliminaar(int $idProyecto, int $id) : ResponseInterface {
        $valor = $this->modelo->eliminar($idProyecto, $id);
         if ($valor === null) {
            return $this->failNotFoud('NO no encotrada');
        }
        return $this->respond(['mensaje' => 'Tarea Eliminada']);
    }



}
