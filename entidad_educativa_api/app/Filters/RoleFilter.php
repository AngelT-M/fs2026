<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $usuario = json_decode($request->getHeaderLine('X-Authenticated-User'), true);
        $rolesPermitidos = array_map('intval', $arguments ?? []);

        if (! is_array($usuario) || ! in_array((int) ($usuario['rol_id'] ?? 0), $rolesPermitidos, true)) {
            return service('response')
                ->setStatusCode(403)
                ->setJSON([
                    'status' => 403,
                    'error' => true,
                    'message' => 'No tienes permiso para realizar esta operación',
                    'data' => null,
                ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
