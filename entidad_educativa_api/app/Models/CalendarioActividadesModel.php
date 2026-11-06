<?php

namespace App\Models;

use CodeIgniter\Model;

class CalendarioActividadesModel extends Model
{
    protected $table = 'calendario_actividades';
    protected $primaryKey = 'actividad_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_final',
        'tipo_evento',
        'usuario_id',
        'nivel_id',
    ];

    public function eventosParaUsuario(array $usuario): array
    {
        if ((int) ($usuario['rol_id'] ?? 0) !== 5) {
            return $this->orderBy('fecha_inicio', 'ASC')->findAll();
        }

        $alumno = model(AlumnosModel::class, false)
            ->select('alumno_id')
            ->where('usuario_id', (int) ($usuario['id_usuario'] ?? 0))
            ->first();
        $aula = $alumno === null
            ? null
            : model(AsignacionGradosModel::class, false)->aulaDeAlumno((int) $alumno['alumno_id']);

        $this->groupStart()->where('nivel_id', null);
        if ($aula !== null) {
            $this->orWhere('nivel_id', (int) $aula['nivel_id']);
        }

        return $this->groupEnd()->orderBy('fecha_inicio', 'ASC')->findAll();
    }

    public function crearEvento(array $datos, int $usuarioId): array
    {
        if (strtotime($datos['fecha_final']) < strtotime($datos['fecha_inicio'])) {
            return ['status' => 422, 'message' => 'La fecha final debe ser posterior a la fecha inicial.', 'data' => null];
        }

        $datos['usuario_id'] = $usuarioId;
        $id = $this->insert($datos, true);

        return $id === false
            ? ['status' => 500, 'message' => 'No fue posible crear el evento.', 'data' => null]
            : ['status' => 201, 'message' => 'Evento creado.', 'data' => $this->find($id)];
    }
}
