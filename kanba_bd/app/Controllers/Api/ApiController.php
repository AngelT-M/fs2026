<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

abstract class ApiController extends ResourceController
{
    protected $format = 'json';

    /**
     * Convert unexpected API errors into a JSON 500 response without exposing details.
     */
    protected function safely(callable $operation): ResponseInterface
    {
        try {
            return $operation();
        } catch (Throwable $exception) {
            log_message('error', 'API error: {message}', ['message' => $exception->getMessage()]);

            return $this->failServerError('Ocurrió un error interno.');
        }
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function jsonBody(): ?array
    {
        $rawBody = $this->request->getBody();
        $body = json_decode($rawBody, true);

        return json_last_error() === JSON_ERROR_NONE && is_array($body) ? $body : null;
    }
}
