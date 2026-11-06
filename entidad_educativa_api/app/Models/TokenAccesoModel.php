<?php

namespace App\Models;

use CodeIgniter\Model;

class TokenAccesoModel extends Model
{
    protected $table            = 'token_acceso';
    protected $primaryKey       = 'token_id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'usuario_id',
        'token',
        'fecha_creacion',
        'fecha_expiracion',
        'activo',
    ];

    public function crearToken(int $idUsuario): string|false
    {
        $token = bin2hex(random_bytes(32));
        $id = $this->insert([
            'usuario_id' => $idUsuario,
            'token' => hash('sha256', $token),
            'fecha_creacion' => gmdate('Y-m-d H:i:s'),
            'fecha_expiracion' => gmdate('Y-m-d H:i:s', time() + 7 * 86400),
            'activo' => 1,
        ], true);

        return $id === false ? false : $token;
    }

    public function usuarioPorToken(string $token): ?array
    {
        $hash = hash('sha256', $token);
        $fila = db_connect()->table('token_acceso')
            ->select('usuarios.usuario_id, usuarios.nom_usuario, usuarios.rol_id, roles.nom_rol AS rol_nombre')
            ->join('usuarios', 'usuarios.usuario_id = token_acceso.usuario_id')
            ->join('roles', 'roles.rol_id = usuarios.rol_id')
            ->where('token_acceso.token', $hash)
            ->where('token_acceso.activo', 1)
            ->where('token_acceso.fecha_expiracion >', gmdate('Y-m-d H:i:s'))
            ->where('usuarios.estado', 'ACTIVO')
            ->get()
            ->getRowArray();
        if ($fila === null) {
            return null;
        }

        return [
            'id_usuario' => (int) $fila['usuario_id'],
            'usuario' => $fila['nom_usuario'],
            'rol_id' => (int) $fila['rol_id'],
            'id_rol' => (int) $fila['rol_id'],
            'rol_nombre' => strtoupper(trim($fila['rol_nombre'])),
        ];
    }

    public function revocarToken(string $token): bool
    {
        return $this->where('token', hash('sha256', $token))
            ->set(['activo' => 0])
            ->update();
    }
}
