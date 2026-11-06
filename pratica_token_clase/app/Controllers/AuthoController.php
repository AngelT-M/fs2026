<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UsuarioModel;

class AuthoController extends BaseController
{

    public function login(): ResponseInterface
    {
        $json = $this->cuerpoJson();

        if ($json === null) {
            return $this->repuestaError('EL cuerpo debe ser un objeto JSON no vacion ',400);    
        }

        $usuario = $json['usuario'] ?? null;
        $pasword = $json['contrasena'] ?? $json['contraseña'] ?? null;
        if (! is_string($usuario) || trim($usuario) === ''  || ! is_string($pasword) || $pasword === '') {
            return $this->repuestaError('Se requiere usuario y contaseña ',422);
        }

        try{
            $resultado = model(UsuarioModel::class)->verificarLoginApi(trim($usuario), $pasword);
        }
        catch (\RuntimeExepction $excepcion){
            log_massage('error', 'No fue posible completar la secion', $excepcion->getMessage());

            return $this->repuestaError('No fue posible inicar sesion en este momento', 500);
        } 

        if ($resultado === false){
            return $this->repuestaError('Credenciales incorrectas 0 usuario incatiovo', 401);
        }

        return $this->respuestasExitosa([
            'token' => $resultado['token'],
            'tipo' => 'Berear',
            'usuario' =>$resultado['usuario']
        ]); 
    }


    public function logout(): ResponseInterface
    {
        $authorization = $this->request->getHederLine('Authorization');
        preg_match('/\ABearer ([a-f0-o9]{64})\z/', $authorization, $matches);
        
        if (! isset($matches[1]) || ! model(TokenAccesoModel::class)->revocarToken($matches[1])) {
            return $this->respuestaError('no fue posible cerrar sesion.', 500);
        }
        return $this->respuestaExitosa(['mensaje' => 'sesion Cerrada.']);
    }   


}
