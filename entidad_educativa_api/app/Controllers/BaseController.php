<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;


abstract class BaseController extends Controller
{
    protected function respuestaExitosa(
        mixed $datos,
        int $estado = 200,
        string $mensaje = 'Operación exitosa'
    ): ResponseInterface
    {
        $respuesta = [
            'status' => $estado,
            'error' => false,
            'message' => $mensaje,
            'data' => $datos,
        ];
        return $this->response->setStatusCode($estado)->setJSON($respuesta);
    }

    protected function respuestaError(string $mensaje, int $estado): ResponseInterface
    {
        $respuesta = [
            'status' => $estado,
            'error' => true,
            'message' => $mensaje,
            'data' => null,
        ];
        return $this->response->setStatusCode($estado)->setJSON($respuesta);
    }

    protected function cuerpoJson(): ?array
    {
        $cuerpo = $this->request->getBody();
        if (! is_string($cuerpo)) {
            return null;
        }
        $json = json_decode($cuerpo, true);

        return is_array($json) && $json !== [] && ! array_is_list($json) ? $json : null;
    }

    protected function usuarioApi(): ?array
    {
        $usuario = json_decode($this->request->getHeaderLine('X-Authenticated-User'), true);
        return is_array($usuario) ? $usuario : null;
    }

    protected function parametrosPaginacion(): ?array
    {
        $pagina = $this->request->getGet('page') ?? '1';
        $porPagina = $this->request->getGet('per_page') ?? '20';

        if (! is_scalar($pagina) || !is_scalar($porPagina)  ) {
            return null;
        }
        $pagina = filter_var($pagina, FILTER_VALIDATE_INT);
        $porPagina = filter_var($porPagina, FILTER_VALIDATE_INT);


        if (
             $pagina === false
            || $pagina < 1
            || $porPagina === false
            || $porPagina < 1
            || $porPagina > 100
            || $pagina > intdiv(PHP_INT_MAX, $porPagina)
        ) {
            return null;
        }
        return ['pagina' => $pagina, 'por_pagina' => $porPagina];
    }
}
