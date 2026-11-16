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
        'bimestre_id',
        'titulo',
        'mensaje',
        'archivo_url',
        'estado',
        'fecha_envio',
        'fecha_autorizacion',
        'personal_id',
    ];

    public function listarApi(): array
    {
        return $this->select(
            'examen_enviados.examen_id, examen_enviados.bimestre_id, bimestres.nombre AS bimestre, ' .
            'examen_enviados.titulo, examen_enviados.mensaje, ' .
            'examen_enviados.archivo_url, examen_enviados.fecha_envio, examen_enviados.estado, ' .
            'maestros.nombre AS maestro_nombre, maestros.apellido AS maestro_apellido, ' .
            'cursos.nom_curso, grados.nom_grado, secciones.nom_seccion, grados_secciones.ciclo'
        )
            ->join(
                'asignacion_curso_maestro',
                'asignacion_curso_maestro.asignacion_curso_maestro_id = examen_enviados.asignacion_curso_maestro_id'
            )
            ->join('maestros', 'maestros.maestro_id = asignacion_curso_maestro.maestro_id')
            ->join('cursos', 'cursos.curso_id = asignacion_curso_maestro.curso_id')
            ->join('grados_secciones', 'grados_secciones.grado_seccion_id = asignacion_curso_maestro.grado_seccion_id')
            ->join('grados', 'grados.grado_id = grados_secciones.grado_id')
            ->join('secciones', 'secciones.seccion_id = grados_secciones.seccion_id')
            ->join('bimestres', 'bimestres.bimestre_id = examen_enviados.bimestre_id', 'left')
            ->orderBy('grados_secciones.ciclo', 'DESC')
            ->orderBy('examen_enviados.fecha_envio', 'DESC')
            ->findAll();
    }

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
