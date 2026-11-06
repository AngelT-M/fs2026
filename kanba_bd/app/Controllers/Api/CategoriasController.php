<?php

namespace App\Controllers\Api;

use App\Models\CategoriaModel;
use CodeIgniter\HTTP\ResponseInterface;

class CategoriasController extends ApiController
{
    public function index(): ResponseInterface
    {
        return $this->safely(function (): ResponseInterface {
            $categories = (new CategoriaModel())
                ->select('id, nombre, color_hex')
                ->findAll();

            return $this->respond(['data' => $categories]);
        });
    }
}
