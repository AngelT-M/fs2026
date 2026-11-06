<?php

namespace App\Models;

use CodeIgniter\Model;

class ExamenEnviadosModel extends Model
{
    protected $table = 'examen_enviados';
    protected $primaryKey = 'examen_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'asignacion_curso_maestro_id',
        'titulo',
        'mensaje',
        'archivo_url',
        'estado',
        'fecha_envio',
        'fecha_autorizacion',
        'personal_id',
    ];

    public function maestroPuedeGestionar(int $asignacionId, array $usuario): bool
    {
        return (int) ($usuario['rol_id'] ?? 0) !== 4
            || model(AsignacionCursoMaestroModel::class, false)
                ->maestroPuedeGestionar($asignacionId, (int) ($usuario['id_usuario'] ?? 0));
    }

    public function enviarExamen(array $datos): array|false
    {
        $datos['estado'] = 'PENDIENTE';
        $datos['fecha_envio'] = date('Y-m-d H:i:s');
        $id = $this->insert($datos, true);

        return $id === false ? false : $this->find($id);
    }

    public function autorizarExamen(int $id, string $estado, int $usuarioId): array
    {
        $examen = $this->find($id);
        if ($examen === null) {
            return ['status' => 404, 'message' => 'No se encontró el examen.', 'data' => null];
        }
        if ($examen['estado'] !== 'PENDIENTE') {
            return ['status' => 409, 'message' => 'Solo se pueden autorizar exámenes pendientes.', 'data' => null];
        }

        $personal = model(PersonalAdministrativosModel::class, false)
            ->select('personal_id')
            ->where('usuario_id', $usuarioId)
            ->first();
        if (! $this->update($id, [
            'estado' => $estado,
            'fecha_autorizacion' => date('Y-m-d H:i:s'),
            'personal_id' => $personal['personal_id'] ?? null,
        ])) {
            return ['status' => 409, 'message' => 'No fue posible actualizar el estado del examen.', 'data' => null];
        }

        return [
            'status' => 200,
            'message' => 'Estado del examen actualizado.',
            'data' => $this->find($id),
        ];
    }
}
