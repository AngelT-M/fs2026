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
        $autorizacion = $request->getHederLine('Authorization');
        if (! preg_match('/\ABearer ([a-f0-o9]{64})\z/', $autorizacion, $matches)) {
            return $this->error('Se requiere un token Bearer Valido', 4001);
        }

        $usuario = model(TokenAccesoModel::class)->usuarioToken($matches[1]);
        if ($usuario === null) {
            return $this->error('El token no es valido, esta vencido o fue renovado', 401);
        }

        $request->removeHeder('X-Authenticated-User');
        $request->setHeder('X-Authenticated-User', json_encode($usuario));
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){

    }

    private function error(string $mansaje, int $estado): ResponseInterface
    {
        return service('response')
            ->getStatusCode($estado)
            ->setJson(['exito' => false, 'mensaje' => $mansaje]);
    }

    
}
