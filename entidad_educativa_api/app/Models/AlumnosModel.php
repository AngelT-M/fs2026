<?php

namespace App\Models;

use CodeIgniter\Model;

class AlumnosModel extends Model
{
    protected $table = 'alumnos';
    protected $primaryKey = 'alumno_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'carne',
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'email',
        'nom_encargado',
        'tel_personal',
        'tel_encargado',
        'usuario_id',
    ];

    public function listarApi(int $pagina, int $porPagina, array $solicitante = []): array
    {
        $consulta = $this->select('alumnos.*')
            ->join('usuarios', 'usuarios.usuario_id = alumnos.usuario_id')
            ->where('usuarios.estado', 'ACTIVO');
        if ((int) ($solicitante['rol_id'] ?? 0) === 5) {
            $consulta->where('alumnos.usuario_id', (int) ($solicitante['id_usuario'] ?? 0));
        }

        return $consulta->orderBy($this->primaryKey, 'ASC')
            ->findAll($porPagina, ($pagina - 1) * $porPagina);
    }

    public function buscarApi(int $id, array $solicitante = []): ?array
    {
        if ((int) ($solicitante['rol_id'] ?? 0) === 5) {
            $this->where('usuario_id', (int) ($solicitante['id_usuario'] ?? 0));
        }

        return $this->select('alumnos.*')
            ->join('usuarios', 'usuarios.usuario_id = alumnos.usuario_id')
            ->where('usuarios.estado', 'ACTIVO')
            ->find($id);
    }

    public function perteneceAUsuario(int $alumnoId, int $usuarioId): bool
    {
        return $this->where('alumno_id', $alumnoId)
            ->where('usuario_id', $usuarioId)
            ->countAllResults() > 0;
    }

    public function registrarAlumnoConUsuario(array $alumno, array $usuario): array|false
    {
        $this->db->transStart();
        $usuario['rol_id'] = 5;
        $usuario['password'] = password_hash($usuario['password'], PASSWORD_DEFAULT);
        $usuario['estado'] = 'ACTIVO';
        $usuarioId = model(UsuariosModel::class, false)->insert($usuario, true);

        if ($usuarioId !== false) {
            $alumno['usuario_id'] = $usuarioId;
            $alumnoId = $this->insert($alumno, true);
        } else {
            $alumnoId = false;
        }

        $this->db->transComplete();

        return $alumnoId !== false && $this->db->transStatus() ? $this->find($alumnoId) : false;
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
        $alumno = $this->find($id);
        if ($alumno === null) {
            return false;
        }

        $this->db->transStart();
        model(UsuariosModel::class, false)->update($alumno['usuario_id'], ['estado' => 'INACTIVO']);
        model(TokenAccesoModel::class, false)
            ->where('usuario_id', $alumno['usuario_id'])
            ->set(['activo' => 0])
            ->update();
        $this->db->transComplete();

        return $this->db->transStatus();
    }
}
