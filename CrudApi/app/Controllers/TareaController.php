<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\TareaModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;

class TareaController extends BaseController
{
    use ResponseTrait;
    private ProTareasModel $modelo;

    public function __construct(){
        $this->modelo = model(ProTareasModel::class);
    }

    public function mostrar(int $idProyecto) :ResponseInterface
    {
        return $this->respond($this->modelo->mostrar($idProyecto));
    }

    public function buscar(int $idProyecto, int $id): ResponseInterface
    {
        $datos = $this->modelo->buscar($idProyecto, $id);
        if ($datos === null) {
            return $this->failNotFound('Tarea no encontrada con el id de proyecto');
        }
        return $this->respond($datos);
    }

    private function cuerpo(): array
    {
        $json = $this->request->getJSON(true);
        if (is_array($json)) {
            return $json;
        }
        return [];
    }

    public function crear(int $idProyecto):ResponseInterface{

        $datos = $this->modelo->crear($idProyecto, $this->cuerpo());
        if ($datos === null) {
            return $this->fail('No fue posible hacer la tarea');
        }
        return $this->respondCreated(['id_tarea' => $datos]);
    }

    public function actualizar(int $idProyecto, int $id): ResponseInterface
    {
        $resultado = $this->modelo->actualizar($idProyecto, $id, $this->cuerpo());
        if ($resultado === null) {
            return $this->failNotFound('Tarea no encotrada en el proyecto');
        }
        return $this->respond(['mensaje'  => 'tarea Actualizada']);
    }

    public function eliminar(int $idProyecto, int $id):ResponseInterface
    {
        $eliminar = $this->modelo->eliminar($idProyecto, $id);
        if ($eliminar === null) {
            return $this->failNotFound('Tarea no encotrado en el proyecto');
        }
        return $this->respond(['mensaje' => 'Tarea eliminada'] );
    }
    





}
