<?php

namespace App\Controllers\Api;

use App\Models\UsuarioModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends ApiController
{
    public function login(): ResponseInterface
    {
        return $this->safely(function (): ResponseInterface {
            $body = $this->jsonBody();

            if ($body === null) {
                return $this->fail('El cuerpo debe ser un JSON válido.', 400);
            }

            $rawEmail = $body['correo'] ?? null;
            $password = $body['password'] ?? null;

            if (! is_string($rawEmail) || ! is_string($password) || $password === '') {
                return $this->fail('Se requiere un correo válido en "correo" y una contraseña.', 400);
            }

            $email = filter_var(trim($rawEmail), FILTER_VALIDATE_EMAIL);
            if ($email === false) {
                return $this->fail('Se requiere un correo válido en "correo" y una contraseña.', 400);
            }

            $user = (new UsuarioModel())->where('correo', $email)->first();

            if ($user === null || ! password_verify($password, $user['password_hash'])) {
                return $this->failUnauthorized('Correo o contraseña incorrectos.');
            }

            return $this->respond([
                'status'       => 200,
                'access_token' => config('Auth')->accessToken,
                'usuario' => [
                    'id'         => $user['id'],
                    'nombre'     => $user['nombre'],
                    'iniciales'  => $user['iniciales'],
                    'avatar_url' => $user['avatar_url'],
                ],
            ]);
        });
    }
}
