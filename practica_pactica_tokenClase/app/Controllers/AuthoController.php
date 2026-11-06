<?php

namespace App\Controllers;


use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UsuarioModel;
use App\Models\TokenAccesoModel;

class AuthoController extends BaseController
{
    public function login(): ResponseInterface
    {
        $json = $this->cuerpoJSON();

        if ($json === null) {
            return $this->resError('EL cuerpo debe ser un objeto JSON no vacion', 400);
        }

        $usuario = $json['usuario'] ?? null;
        $contrasena =$json['contrasena'] ?? $json['contraseña'] ?? null;
        if (! is_string($usuario) || trim($usuario) === '' || ! is_string($contrasena) || $contrasena === '') {
            return $this->resError('Se requiere usuario y contraseña', 422);
        }

        try{
            $resultado = model(UsuarioModel::class)->verificarLoginApi(trim($usuario), $contrasena);
        }
        catch(\RetuntimeExeption $exepcion){
            log_masage('error', 'No fue posible completar la secion', $exepcion->getMessage());
            return $this->resError('No fue posible inicario sesion en este momento', 500);
        }
        if ($resultado === false) {
            return $this->resError('Creedencial incorrecta o usuario inactivo', 4001);
        }
        return $this->resExitosa([
            'token' => $resultado['token'],
            'tipo' => 'Berear',
            'usuairo' => $resultado['usuario']
        ]);
    }
    

    public function logout():ResponseInterface
    {
        $autorizacion = $this->request->getHeaderLine('Authorization');
        preg_match('/\ABearer ([a-f0-o9]{64})\z/', $autorizacion, $matches);

        if (isset($matches[1]) || !model(TokenAccesoModel::class)->revocarToken($matches[1]) ) {
            return $this->resError('no fue posible cerrar sesion', 500);
        }
        return $this->resExitosa(['mensaje' => 'sesion Cerrada.']);
    }

}
