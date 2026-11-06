<?php

namespace App\Controllers\Api;

use App\Models\UsuarioModel;
use CodeIgniter\HTTP\ResponseInterface;

class UsuariosController extends ApiController
{
    public function index(): ResponseInterface
    {
        return $this->safely(function (): ResponseInterface {
            $users = (new UsuarioModel())
                ->select('id, nombre, iniciales, avatar_url')
                ->findAll();

            return $this->respond(['data' => $users]);
        });
    }
}
