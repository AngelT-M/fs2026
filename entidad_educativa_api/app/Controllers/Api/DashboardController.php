<?php

namespace App\Controllers\Api;

use App\Models\DashboardModel;
use CodeIgniter\HTTP\ResponseInterface;

class DashboardController extends ApiController
{
    public function obtenerResumen(): ResponseInterface
    {
        return $this->respuestaExitosa(
            model(DashboardModel::class, false)->obtenerResumenGeneral()
        );
    }
}
