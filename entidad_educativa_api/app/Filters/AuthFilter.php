<?php

namespace App\Filters;

use App\Models\TokenAccesoModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! preg_match('/\ABearer ([a-f0-9]{64})\z/', $request->getHeaderLine('Authorization'), $matches)) {
            return $this->error('Se requiere un token Bearer válido.', 401);
        }

        $usuario = model(TokenAccesoModel::class)->usuarioPorToken($matches[1]);
        if ($usuario === null) {
            return $this->error('El token no es válido, está vencido o fue revocado.', 401);
        }

        $request->setHeader('X-Authenticated-User', json_encode($usuario));
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }

    private function error(string $message, int $status): ResponseInterface
    {
        return service('response')
            ->setStatusCode($status)
            ->setJSON([
                'status' => $status,
                'error' => true,
                'message' => $message,
                'data' => null,
            ]);
    }
}
