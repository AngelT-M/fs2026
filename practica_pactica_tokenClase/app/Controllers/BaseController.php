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
    protected function resExitosa(mixed $datos, int $estado = 200, ?array $meta = null): ResponseInterface
    {
        $respuesta = ['exito' => true, 'datos' => $datos];
        if ($meta !== null) {
            $respuesta['meta'] = $meta;
        }
        return $this->response->setStatusCode($estado)->setJSON($respuesta);
    }

    protected function resError(string $mensaje, int $estado, array $errores = []): ResponseInterface
    {
        $respuesta = ['exito' => false, 'mensaje' =>$mensaje];
        if ($errores !== []) {
            $respuesta['errores'] = $errores;
        }
        return $this->response->setStatusCode($estado)->setJSON($respuesta);
    }

    protected function cuerpoJSON():?array
    {
        $cuerpo = $this->request->getBody();
        if (! is_string($cuerpo)) {
            return null;
        }
        $json = json_decode($cuerpo, true);
        return is_array($json) && $json !== [] && ! array_is_list($json) ? $json: null;
    }

    protected function usuarioApi(): ?array
    {
        $usuario= json_decode($this->getHeaderLine('X-Authenticated-User'), true);
        return is_array($usuario)? $usuario: null;
    }
    protected function esAdministrador(?array $usuario):bool
    {
        return($usuario['rol_nombre']?? null)=== 'administrador';
    }

    protected function parametroPaginacion():?array
    {
        $pagina = $this->request->getGet('page') ?? '1';
        $porPagina = $this->request->getGet('per_page') ?? '20';
        
        
        if (! is_scalar($pagina) || ! is_scalar($porPagina)) { 
            return null;
        }

        $pagina = filter_var($pagina, FILTER_VALIDATE_INT); 
        $porPagina = filter_var($porPagina, FILTER_VALIDATE_INT);

        if (
            $pagina === false 
            || $pagina < 1
            || $porPagina === false
            || $porPagina < 1
            || $porPagina >100
            || $pagina > intdiv(PHP_INT_MAX, $porPagina)
        ) {
           return null;
        }
        return ['pagina' => $pagina, 'por_Pagina' => $porPagina];
    }

    protected function erroresValidacion(array $datos, array $reglas): array
    {
        $valiacion = service('validation');
        $valiacion->setRules($reglas);
        return $valiacion->run($datos)? [] : $valiacion->getErrors();
    }
    




}
