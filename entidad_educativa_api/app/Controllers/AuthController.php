<?php

namespace App\Controllers;


use App\Models\TokenAccesoModel;
use App\Models\UsuariosModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    public function login(): ResponseInterface
    {
        $json = $this->cuerpoJson();
        if ($json === null) {
            return $this->respuestaError('El cuerpo debe ser un objeto JSON válido.', 400);
        }

        $usuario = $json['usuario'] ?? null;
        $password = $json['contrasena'] ?? $json['contraseña'] ?? null;
        if (! is_string($usuario) || trim($usuario) === '' || ! is_string($password) || $password === '') {
            return $this->respuestaError('Se requiere usuario y contraseña.', 422);
        }

        $cuenta = model(UsuariosModel::class)
            ->select('usuarios.usuario_id, usuarios.nom_usuario, usuarios.password, usuarios.rol_id, roles.nom_rol AS rol_nombre')
            ->join('roles', 'roles.rol_id = usuarios.rol_id')
            ->where('usuarios.nom_usuario', trim($usuario))
            ->where('usuarios.estado', 'ACTIVO')
            ->first();
        if ($cuenta === null || ! password_verify($password, $cuenta['password'])) {
            return $this->respuestaError('Credenciales incorrectas o usuario inactivo.', 401);
        }

        $token = model(TokenAccesoModel::class)->crearToken((int) $cuenta['usuario_id']);
        if ($token === false) {
            return $this->respuestaError('No fue posible iniciar sesión en este momento.', 500);
        }

        return $this->respuestaExitosa([
            'token' => $token,
            'tipo' => 'Bearer',
            'usuario' => [
                'id_usuario' => (int) $cuenta['usuario_id'],
                'usuario' => $cuenta['nom_usuario'],
                'rol_id' => (int) $cuenta['rol_id'],
                'id_rol' => (int) $cuenta['rol_id'],
                'rol_nombre' => strtoupper(trim($cuenta['rol_nombre'])),
            ],
        ], 200, 'Inicio de sesión exitoso.');
    }


    public function logout(): ResponseInterface
    {
        $authorization = $this->request->getHeaderLine('Authorization');
        preg_match('/\ABearer ([a-f0-9]{64})\z/', $authorization, $matches);

        if (! isset($matches[1]) || ! model(TokenAccesoModel::class)->revocarToken($matches[1])) {
            return $this->respuestaError('No fue posible cerrar la sesión.', 401);
        }
        return $this->respuestaExitosa(null, 200, 'Sesión cerrada.');
    }



}
