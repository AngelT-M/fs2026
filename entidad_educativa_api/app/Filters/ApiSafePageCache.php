<?php

namespace App\Filters;

use CodeIgniter\Filters\PageCache;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class ApiSafePageCache extends PageCache
{
    public function before(RequestInterface $request, $arguments = null): ?ResponseInterface
    {
        return $this->esRutaApi($request) ? null : parent::before($request, $arguments);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $this->esRutaApi($request) ? null : parent::after($request, $response, $arguments);
    }

    private function esRutaApi(RequestInterface $request): bool
    {
        $path = trim($request->getUri()->getPath(), '/');

        return $path === 'v1'
            || str_starts_with($path, 'v1/')
            || $path === 'api'
            || str_starts_with($path, 'api/');
    }
}
