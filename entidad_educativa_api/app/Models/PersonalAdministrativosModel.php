<?php

namespace App\Models;

use CodeIgniter\Model;

class PersonalAdministrativosModel extends Model
{
    protected $table = 'personal_administrativos';
    protected $primaryKey = 'personal_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'usuario_id',
        'nombre',
        'apellido',
        'cargo',
        'email',
        'telefono',
    ];

    public function listarApi(int $pagina, int $porPagina, array $solicitante = []): array|false
    {
        if ((int) ($solicitante['rol_id'] ?? 0) === 5) {
            return false;
        }

        return $this->select('personal_administrativos.*')
            ->join('usuarios', 'usuarios.usuario_id = personal_administrativos.usuario_id')
            ->where('usuarios.estado', 'ACTIVO')
            ->orderBy($this->primaryKey, 'ASC')
            ->findAll($porPagina, ($pagina - 1) * $porPagina);
    }

    public function buscarApi(int $id, array $solicitante = []): ?array
    {
        if ((int) ($solicitante['rol_id'] ?? 0) === 5) {
            return null;
        }

        return $this->select('personal_administrativos.*')
            ->join('usuarios', 'usuarios.usuario_id = personal_administrativos.usuario_id')
            ->where('usuarios.estado', 'ACTIVO')
            ->find($id);
    }

    public function registrarPersonalConUsuario(array $personal, array $usuario): array|false
    {
        $this->db->transStart();
        $usuario['rol_id'] = 3;
        $usuario['password'] = password_hash($usuario['password'], PASSWORD_DEFAULT);
        $usuario['estado'] = 'ACTIVO';
        $usuarioId = model(UsuariosModel::class, false)->insert($usuario, true);
        if ($usuarioId !== false) {
            $personal['usuario_id'] = $usuarioId;
            $personalId = $this->insert($personal, true);
        } else {
            $personalId = false;
        }
        $this->db->transComplete();

        return $personalId !== false && $this->db->transStatus() ? $this->find($personalId) : false;
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
        $personal = $this->find($id);
        if ($personal === null) {
            return false;
        }

        $this->db->transStart();
        model(UsuariosModel::class, false)->update($personal['usuario_id'], ['estado' => 'INACTIVO']);
        model(TokenAccesoModel::class, false)
            ->where('usuario_id', $personal['usuario_id'])
            ->set(['activo' => 0])
            ->update();
        $this->db->transComplete();

        return $this->db->transStatus();
    }
}
