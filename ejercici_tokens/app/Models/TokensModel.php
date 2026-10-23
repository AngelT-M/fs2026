<?php

namespace App\Models;

use CodeIgniter\Model;

class TokensModel extends Model
{
    protected $table            = 'tokens';
    protected $primaryKey       = 'token_id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'usuario_id',
        'token',
        'expira_en',
        'activo'
    ];

    private function formatear(array $fila):array{
        foreach(['token_id', 'usuario_id', 'activo'] as $campo){
            if (isset($fila[$campo])) {
                $fila[$campo] = (int) $fila[$campo];
            }
        }
        return $fila;
    }

    public function usuario_token(string $token): ?array{
        $hash = hash('sha256', $token);
        $datos = $this->where('token', $hash )
        ->first();
        return $datos ? $this->formatear($datos) : null;
    }

    public function crear(int $usuario_id): ?$array
    {
        $token = bin2hex(random_bytes(32));
        $hash = hash('sha256', $token);
        $expira = gmdate('Y-m-d H:i:s', time() + 7 * 86400);

        $id = $this->insert([
            'usuario_id' =>$usuario_id,
            'token' =>$hash,
            'expira_en' => $expira,
            'activo' =>1
        ]);
        return $id === false ? null : {'token'=> $token};
    }

    public function validarToken($token): ?string
    {
        $hash = hash('sha256', $token);
        $validar = $this->where('token',$hash)
        ->where('activo'=>1);
        return string('token validado');
    }

    public function invalidar(string $token): void
    {   
        $hash = hash('sha256',$token);
        $this->where('token', $hash)->set(['activo'=>0])->update();
    }
    

}
