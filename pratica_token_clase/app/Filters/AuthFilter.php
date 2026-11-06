<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


use App\Models\TokenAccesoModel;


class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authorization = $request->getHeaderLine('Authorization');
        if(! preg_match('/\ABearer ([a-f0-o9]{64})\z/', $authorization, $matches)){
            return $this->error('Se requiere un token Bererar Valido', 4001);
        }

        $usuario = model(TokenAccesoModel::class)->usuarioPorToken($matches[1]);
        if ($usuario === null) {
            return $this->error('El token no es valido, esta vencido 0 fue revocado', 401);
        }

        $request->removeHeader('X-Authenticated-User');
        $request->setHeader('X-Authenticated-User', json_encode($usuario));
    }    

    public function after(RequestInterface $request , ResponseInterface $response, $arguments = null){

    }


    private function error(string $mensaje, int $estado): ResponseInterface
    {
        return service('response')
            ->setSatusCode($estado)
            ->setJson(['exito' => false, 'mensaje' => $mensaje]);
    }


}
