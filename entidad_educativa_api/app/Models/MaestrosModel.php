<?php

namespace App\Models;

use CodeIgniter\Model;

class MaestrosModel extends Model
{
    protected $table = 'maestros';
    protected $primaryKey = 'maestro_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'carne_maestro',
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'email',
        'tel_personal',
        'tel_casa',
        'usuario_id',
    ];

    public function listarApi(int $pagina, int $porPagina, array $solicitante = []): array|false
    {
        if ((int) ($solicitante['rol_id'] ?? 0) === 5) {
            return false;
        }

        return $this->select('maestros.*')
            ->join('usuarios', 'usuarios.usuario_id = maestros.usuario_id')
            ->where('usuarios.estado', 'ACTIVO')
            ->orderBy($this->primaryKey, 'ASC')
            ->findAll($porPagina, ($pagina - 1) * $porPagina);
    }

    public function buscarApi(int $id, array $solicitante = []): ?array
    {
        if ((int) ($solicitante['rol_id'] ?? 0) === 5) {
            return null;
        }

        return $this->select('maestros.*')
            ->join('usuarios', 'usuarios.usuario_id = maestros.usuario_id')
            ->where('usuarios.estado', 'ACTIVO')
            ->find($id);
    }

    public function registrarMaestroConUsuario(array $maestro, array $usuario): array|false
    {
        $this->db->transStart();
        $usuario['rol_id'] = 4;
        $usuario['password'] = password_hash($usuario['password'], PASSWORD_DEFAULT);
        $usuario['estado'] = 'ACTIVO';
        $usuarioId = model(UsuariosModel::class, false)->insert($usuario, true);
        if ($usuarioId !== false) {
            $maestro['usuario_id'] = $usuarioId;
            $maestroId = $this->insert($maestro, true);
        } else {
            $maestroId = false;
        }
        $this->db->transComplete();

        return $maestroId !== false && $this->db->transStatus() ? $this->find($maestroId) : false;
    }

    public function actualizarApi(int $id, array $datos): bool
    {
        return $this->update($id, $datos);
    }

    public function actualizarYObtenerApi(int $id, array $datos): array|false
    {
        return $this->update($id, $datos) ? $this->find($id) : false;
    }

    public function desactivarApi(int $id): bool
    {
        $maestro = $this->find($id);
        if ($maestro === null) {
            return false;
        }

        $this->db->transStart();
        model(UsuariosModel::class, false)->update($maestro['usuario_id'], ['estado' => 'INACTIVO']);
        model(TokenAccesoModel::class, false)
            ->where('usuario_id', $maestro['usuario_id'])
            ->set(['activo' => 0])
            ->update();
        $this->db->transComplete();

        return $this->db->transStatus();
    }
}
