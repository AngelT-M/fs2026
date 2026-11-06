<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

abstract class ApiController extends BaseController
{
    protected function datosEntrada(): ?array
    {
        return $this->cuerpoJson();
    }

    protected function validarEntrada(array $datos, array $reglas): ?ResponseInterface
    {
        if ($this->validateData($datos, $reglas)) {
            return null;
        }

        return $this->respuestaError(
            'Los datos enviados no son válidos: ' . implode('; ', service('validation')->getErrors()),
            422
        );
    }

    protected function validarId(mixed $id): ?int
    {
        $validId = filter_var($id, FILTER_VALIDATE_INT);
        return $validId !== false && $validId > 0 ? $validId : null;
    }

    protected function usuarioActual(): array
    {
        return $this->usuarioApi() ?? [];
    }
}
