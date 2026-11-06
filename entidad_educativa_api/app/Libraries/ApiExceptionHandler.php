<?php

namespace App\Libraries;

use CodeIgniter\Debug\BaseExceptionHandler;
use CodeIgniter\Debug\ExceptionHandler;
use CodeIgniter\Debug\ExceptionHandlerInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

class ApiExceptionHandler extends BaseExceptionHandler implements ExceptionHandlerInterface
{
    public function handle(
        Throwable $exception,
        RequestInterface $request,
        ResponseInterface $response,
        int $statusCode,
        int $exitCode,
    ): void {
        $path = trim($request->getUri()->getPath(), '/');
        if ($path !== 'v1' && ! str_starts_with($path, 'v1/')) {
            (new ExceptionHandler($this->config))->handle($exception, $request, $response, $statusCode, $exitCode);
            return;
        }

        log_message('critical', 'Error no controlado en API: ' . $exception->getMessage());
        $message = $statusCode === 404 ? 'Ruta API no encontrada.' : 'Error interno del servidor.';
        $response->setStatusCode($statusCode)->setJSON([
            'status' => $statusCode,
            'error' => true,
            'message' => $message,
            'data' => null,
        ])->send();

        if (ENVIRONMENT !== 'testing') {
            exit($exitCode);
        }
    }
}
