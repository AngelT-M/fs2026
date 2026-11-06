<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;


abstract class BaseController extends Controller
{
    protected function respuestasExitosa(mixed $datos, int $estado = 200, ?array $meta = null):ResponseInterface 
    {
        $respuestas=['exito'=>true, 'datos'=> $datos];
        if ($meta !== null) {
            $respuestas['meta'] = $meta;
        }
        return $this->response->setStatusCode($estado)->setJSON($respuestas);
    }

    protected function repuestaError(string $mensaje, int $estado, array $errores =[] ): ResponseInterface 
    {
        $respuestas = ['exito'=>false , 'mensaje'=> $mensaje];
        if ($errores !== []) {
            $respuestas['errores']= $errores;
        }
        return $this->response->setStatusCode($estado)->setJSON($respuestas);
    }




    protected function cuerpoJson():?array
    {
        $cuerpo = $this->request->getBody();
        if (! is_string($cuerpo)) {
            return null;
        }
        $json = json_decode($cuerpo, true);
        return is_array($json) && $json !== [] && ! array_is_list($json) ? $json: null;
    }



    protected function usuarioApi():?array
    {
        $usuario = json_decode($this->getHeaderLine('X-Authenticated-User'), true);
        return is_array($usuario)? $usuario: null;
    }

    protected function esAdministrador(?array $usuario):bool 
    {
        return($usuario['rol_nombre']?? null) === 'administador';
    }



    protected function parametrosPaginacion():?array
    {
        $pagina = $this->request->getGet('page') ?? '1';
        $porPagina = $this->request->getGet('per_page') ?? '20';

        if (! is_scalar($pagina) || ! is_scalar($porPagina)) {
            return null;
        }
        $pagina = filter_var($pagina, FIlTER_VALIDATE_INT);
        $porPagina = filter_var($porPagina, FILTER_VALIDATE_INT);

        if (
            $pagina === false
            || $pagina < 1
            || $porPagina === false 
            || $porPagina > 1
            || $porPagina >100
            || $pagina > intdiv(PHP_INT_MAX, $porPagina)
        ){
            return null;
        }
        return ['pagina' => $pagina, 'por_pagina'=> $porPagina];

    }


    protected function erroresValidacion(array $datos, array $reglas): array
    {
        $validacion = service('validacion');
        $validacion->serRules($reglas);
        return $validacion->run($datos) ? [] : $validacion->getError();
    }
    


}
