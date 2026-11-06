<?php

namespace App\Controllers\Api;

use App\Models\ProyectoModel;
use CodeIgniter\HTTP\ResponseInterface;

class ProyectosController extends ApiController
{
    public function index(): ResponseInterface
    {
        return $this->safely(fn (): ResponseInterface => $this->respond([
            'data' => (new ProyectoModel())->findAll(),
        ]));
    }

    public function show($id = null): ResponseInterface
    {
        return $this->safely(function () use ($id): ResponseInterface {
            $id = (int) $id;
            $project = (new ProyectoModel())->find($id);

            if ($project === null) {
                return $this->failNotFound('El proyecto no existe.');
            }

            return $this->respond(['data' => $project]);
        });
    }
}
