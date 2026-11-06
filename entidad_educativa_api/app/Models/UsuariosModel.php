<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuariosModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'usuario_id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'nom_usuario',
        'password',
        'rol_id',
        'estado',
    ];

    public function iniciarSesion(string $nombre, string $password): array|false|null
    {
        $usuario = $this->select('usuarios.usuario_id, usuarios.nom_usuario, usuarios.password, usuarios.rol_id, roles.nom_rol AS rol_nombre')
            ->join('roles', 'roles.rol_id = usuarios.rol_id')
            ->where('usuarios.nom_usuario', $nombre)
            ->where('usuarios.estado', 'ACTIVO')
            ->first();

        if ($usuario === null || ! password_verify($password, $usuario['password'])) {
            return null;
        }

        $token = model(TokenAccesoModel::class, false)->crearToken((int) $usuario['usuario_id']);
        if ($token === false) {
            return false;
        }

        return [
            'token' => $token,
            'usuario' => [
                'id_usuario' => (int) $usuario['usuario_id'],
                'usuario' => $usuario['nom_usuario'],
                'rol_id' => (int) $usuario['rol_id'],
                'id_rol' => (int) $usuario['rol_id'],
                'rol_nombre' => match (strtoupper(trim($usuario['rol_nombre']))) {
                    'ADMIN' => 'ADMINISTRADOR',
                    default => strtoupper(trim($usuario['rol_nombre'])),
                },
            ],
        ];
    }

    public function registrarUsuario(array $datos): array|false
    {
        return $this->crearApi($datos);
    }

    public function verificarCredenciales(string $nombre, string $password): ?array
    {
        $usuario = $this->select('usuarios.usuario_id, usuarios.nom_usuario, usuarios.password, usuarios.rol_id, roles.nom_rol AS rol_nombre')
            ->join('roles', 'roles.rol_id = usuarios.rol_id')
            ->where('usuarios.nom_usuario', $nombre)
            ->where('usuarios.estado', 'ACTIVO')
            ->first();

        if ($usuario === null || ! password_verify($password, $usuario['password'])) {
            return null;
        }

        return [
            'id_usuario' => (int) $usuario['usuario_id'],
            'usuario' => $usuario['nom_usuario'],
            'rol_id' => (int) $usuario['rol_id'],
            'id_rol' => (int) $usuario['rol_id'],
            'rol_nombre' => match (strtoupper(trim($usuario['rol_nombre']))) {
                'ADMIN' => 'ADMINISTRADOR',
                default => strtoupper(trim($usuario['rol_nombre'])),
            },
        ];
    }

    public function listarApi(int $pagina, int $porPagina, array $solicitante): array
    {
        if ((int) ($solicitante['rol_id'] ?? 0) === 5) {
            $this->where('usuario_id', (int) ($solicitante['id_usuario'] ?? 0));
        }

        $usuarios = $this->where('estado', 'ACTIVO')
            ->orderBy($this->primaryKey, 'ASC')
            ->findAll($porPagina, ($pagina - 1) * $porPagina);
        foreach ($usuarios as &$usuario) {
            unset($usuario['password']);
        }

        return $usuarios;
    }

    public function buscarApi(int $id, array $solicitante): ?array
    {
        if ((int) ($solicitante['rol_id'] ?? 0) === 5) {
            $this->where('usuario_id', (int) ($solicitante['id_usuario'] ?? 0));
        }

        $usuario = $this->where('estado', 'ACTIVO')->find($id);
        if ($usuario !== null) {
            unset($usuario['password']);
        }

        return $usuario;
    }

    public function guardarApi(array $datos): int|false
    {
        $datos['password'] = password_hash($datos['password'], PASSWORD_DEFAULT);
        $datos['estado'] ??= 'ACTIVO';

        return $this->insert($datos, true);
    }

    public function crearApi(array $datos): array|false
    {
        $id = $this->guardarApi($datos);
        return $id === false ? false : $this->buscarApi($id, []);
    }

    public function actualizarApi(int $id, array $datos): bool
    {
        if (isset($datos['password'])) {
            $datos['password'] = password_hash($datos['password'], PASSWORD_DEFAULT);
        }

        $this->db->transStart();
        $actualizado = $this->update($id, $datos);
        if ($actualizado && (isset($datos['password']) || ($datos['estado'] ?? null) === 'INACTIVO')) {
            model(TokenAccesoModel::class, false)
                ->where('usuario_id', $id)
                ->set(['activo' => 0])
                ->update();
        }
        $this->db->transComplete();

        return $actualizado && $this->db->transStatus();
    }

    public function actualizarYObtenerApi(int $id, array $datos): array|false
    {
        if (! $this->actualizarApi($id, $datos)) {
            return false;
        }

        return $this->buscarApi($id, []);
    }

    public function desactivarApi(int $id): bool
    {
        $this->db->transStart();
        $this->update($id, ['estado' => 'INACTIVO']);
        model(TokenAccesoModel::class, false)
            ->where('usuario_id', $id)
            ->set(['activo' => 0])
            ->update();
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    public function perfilPorId(int $usuarioId): ?array
    {
        $usuario = $this->select('usuarios.usuario_id, usuarios.nom_usuario, usuarios.rol_id, roles.nom_rol AS rol_nombre')
            ->join('roles', 'roles.rol_id = usuarios.rol_id')
            ->where('usuarios.usuario_id', $usuarioId)
            ->where('usuarios.estado', 'ACTIVO')
            ->first();

        if ($usuario === null) {
            return null;
        }

        $tablaPerfil = match ((int) $usuario['rol_id']) {
            4 => 'maestros',
            5 => 'alumnos',
            3 => 'personal_administrativos',
            default => null,
        };
        if ($tablaPerfil !== null) {
            $usuario['perfil'] = $this->db->table($tablaPerfil)
                ->where('usuario_id', $usuarioId)
                ->get()
                ->getRowArray();
        }

        return $usuario;
    }

    public function cambiarPassword(int $usuarioId, string $nuevoPassword, ?string $passwordActual = null): bool
    {
        $usuario = $this->select('usuario_id, password')
            ->where('usuario_id', $usuarioId)
            ->where('estado', 'ACTIVO')
            ->first();
        if ($usuario === null || ($passwordActual !== null && ! password_verify($passwordActual, $usuario['password']))) {
            return false;
        }

        $this->db->transStart();
        $actualizado = $this->update($usuarioId, ['password' => password_hash($nuevoPassword, PASSWORD_DEFAULT)]);
        if ($actualizado) {
            model(TokenAccesoModel::class, false)
                ->where('usuario_id', $usuarioId)
                ->set(['activo' => 0])
                ->update();
        }
        $this->db->transComplete();

        return $actualizado && $this->db->transStatus();
    }
}
