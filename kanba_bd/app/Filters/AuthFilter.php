<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authorization = $request->getHeaderLine('Authorization');
        $token = null;

        if (preg_match('/^Bearer\s+(\S+)$/i', trim($authorization), $matches) === 1) {
            $token = $matches[1];
        }

        if ($token !== null && hash_equals(config('Auth')->accessToken, $token)) {
            return null;
        }

        return service('response')
            ->setJSON([
                'status'  => 401,
                'message' => 'Sesión no válida o falta de permisos',
            ])
            ->setStatusCode(401);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
