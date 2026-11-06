<?php

namespace App\Controllers\Api;

use App\Models\TokenAccesoModel;
use App\Models\UsuariosModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends ApiController
{
    public function login(): ResponseInterface
    {
        $datos = $this->datosEntrada();
        if ($datos === null) {
            return $this->respuestaError('El cuerpo debe ser un objeto JSON válido.', 400);
        }

        if (
            ! is_string($datos['nom_usuario'] ?? null)
            || trim($datos['nom_usuario']) === ''
            || ! is_string($datos['password'] ?? null)
            || $datos['password'] === ''
        ) {
            return $this->respuestaError('Se requiere nombre de usuario y contraseña válidos.', 422);
        }

        if (! $this->validateData($datos, [
            'nom_usuario' => 'required|max_length[50]',
            'password' => 'required|max_length[255]',
        ])) {
            return $this->respuestaError(
                'Los datos enviados no son válidos: ' . implode('; ', service('validation')->getErrors()),
                422
            );
        }

        $session = model(UsuariosModel::class, false)->iniciarSesion(
            trim($datos['nom_usuario']),
            $datos['password']
        );
        if ($session === null) {
            return $this->respuestaError('Credenciales incorrectas o usuario inactivo.', 401);
        }
        if ($session === false) {
            return $this->respuestaError('No fue posible iniciar sesión en este momento.', 500);
        }

        return $this->respuestaExitosa([
            'token' => $session['token'],
            'tipo' => 'Bearer',
            'usuario' => $session['usuario'],
        ], 200, 'Inicio de sesión exitoso.');
    }

    public function logout(): ResponseInterface
    {
        preg_match('/\ABearer ([a-f0-9]{64})\z/', $this->request->getHeaderLine('Authorization'), $matches);
        if (! isset($matches[1]) || ! model(TokenAccesoModel::class)->revocarToken($matches[1])) {
            return $this->respuestaError('No fue posible cerrar la sesión.', 401);
        }

        return $this->respuestaExitosa(null, 200, 'Sesión cerrada.');
    }

    public function me(): ResponseInterface
    {
        $usuario = $this->usuarioActual();
        $perfil = model(UsuariosModel::class)->perfilPorId((int) ($usuario['id_usuario'] ?? 0));
        if ($perfil === null) {
            return $this->respuestaError('No se encontró el perfil del usuario.', 404);
        }

        return $this->respuestaExitosa($perfil);
    }
}
