<?php

namespace App\Models;

use CodeIgniter\Model;

class AsistenciaModel extends Model
{
    protected $table = 'asistencia';
    protected $primaryKey = 'asistencia_id';
    protected $returnType = 'array';
    protected $allowedFields = ['usuario_id', 'asignacion_grado_id', 'fecha', 'estado'];

    public function listarPorAulaYFecha(int $gradoSeccionId, string $fecha, array $usuario = []): array|false|null
    {
        if (model(GradosSeccionesModel::class, false)->find($gradoSeccionId) === null) {
            return null;
        }
        if (! $this->puedeConsultarAula($gradoSeccionId, $usuario)) {
            return false;
        }

        return $this->db->table('asignacion_grados ag')
            ->select('ag.asignacion_grado_id, ag.grado_seccion_id, al.alumno_id, al.carne, al.nombre, al.apellido, asi.asistencia_id, asi.fecha, asi.estado')
            ->join('alumnos al', 'al.alumno_id = ag.alumno_id')
            ->join('usuarios u', 'u.usuario_id = al.usuario_id')
            ->join(
                'asistencia asi',
                'asi.asignacion_grado_id = ag.asignacion_grado_id AND asi.usuario_id = al.usuario_id AND asi.fecha = ' . $this->db->escape($fecha),
                'left'
            )
            ->where('ag.grado_seccion_id', $gradoSeccionId)
            ->where('u.estado', 'ACTIVO')
            ->orderBy('al.apellido')
            ->orderBy('al.nombre')
            ->get()
            ->getResultArray();
    }

    public function obtenerHistorialAlumno(int $alumnoId, array $usuario = []): array|false
    {
        if (! $this->puedeConsultarAlumno($alumnoId, $usuario)) {
            return false;
        }

        $historial = $this->db->table('asistencia asi')
            ->select('asi.asistencia_id, asi.fecha, asi.estado, asi.asignacion_grado_id, gs.ciclo, g.nom_grado, s.nom_seccion')
            ->join('asignacion_grados ag', 'ag.asignacion_grado_id = asi.asignacion_grado_id')
            ->join('alumnos al', 'al.alumno_id = ag.alumno_id AND al.usuario_id = asi.usuario_id')
            ->join('grados_secciones gs', 'gs.grado_seccion_id = ag.grado_seccion_id')
            ->join('grados g', 'g.grado_id = gs.grado_id')
            ->join('secciones s', 's.seccion_id = gs.seccion_id')
            ->where('al.alumno_id', $alumnoId)
            ->orderBy('asi.fecha', 'DESC')
            ->get()
            ->getResultArray();

        $resumen = ['total' => count($historial), 'presentes' => 0, 'ausentes' => 0, 'tardes' => 0];
        foreach ($historial as $registro) {
            match ($registro['estado']) {
                'PRESENTE' => $resumen['presentes']++,
                'AUSENTE' => $resumen['ausentes']++,
                'TARDE' => $resumen['tardes']++,
                default => null,
            };
        }

        return ['resumen' => $resumen, 'historial' => $historial];
    }

    public function registrarAsistenciaDiaria(array $datos, array $usuario): array
    {
        $asignacion = model(AsignacionGradosModel::class, false)
            ->alumnoEnAula((int) $datos['alumno_id'], (int) $datos['asignacion_grado_id']);
        if ($asignacion === null) {
            return ['status' => 422, 'message' => 'El alumno no está asignado a esa aula.', 'data' => null];
        }

        if ((int) ($usuario['rol_id'] ?? 0) === 4) {
            $maestro = model(MaestrosModel::class, false)
                ->select('maestro_id')
                ->where('usuario_id', (int) ($usuario['id_usuario'] ?? 0))
                ->first();
            $maestroPuedeGestionar = $maestro !== null
                && model(AsignacionCursoMaestroModel::class, false)
                    ->where('grado_seccion_id', $asignacion['grado_seccion_id'])
                    ->where('maestro_id', $maestro['maestro_id'])
                    ->countAllResults() > 0;
            if (! $maestroPuedeGestionar) {
                return ['status' => 403, 'message' => 'No tienes acceso a esta aula.', 'data' => null];
            }
        }

        $exists = $this->where('usuario_id', $asignacion['usuario_id'])
            ->where('asignacion_grado_id', $datos['asignacion_grado_id'])
            ->where('fecha', $datos['fecha'])
            ->countAllResults();
        if ($exists > 0) {
            return ['status' => 409, 'message' => 'Ya existe asistencia para ese alumno, aula y fecha.', 'data' => null];
        }

        $id = $this->insert([
            'usuario_id' => $asignacion['usuario_id'],
            'asignacion_grado_id' => $datos['asignacion_grado_id'],
            'fecha' => $datos['fecha'],
            'estado' => $datos['estado'],
        ], true);
        if ($id === false) {
            return ['status' => 500, 'message' => 'No fue posible registrar la asistencia.', 'data' => null];
        }

        return [
            'status' => 201,
            'message' => 'Asistencia registrada.',
            'data' => [
                'asistencia_id' => (int) $id,
                'usuario_id' => (int) $asignacion['usuario_id'],
                'asignacion_grado_id' => (int) $datos['asignacion_grado_id'],
                'fecha' => $datos['fecha'],
                'estado' => $datos['estado'],
            ],
        ];
    }

    private function puedeConsultarAula(int $gradoSeccionId, array $usuario): bool
    {
        $rolId = (int) ($usuario['rol_id'] ?? 0);
        if (in_array($rolId, [1, 2, 3], true)) {
            return true;
        }
        if ($rolId !== 4) {
            return false;
        }

        return model(AsignacionCursoMaestroModel::class, false)
            ->join('maestros', 'maestros.maestro_id = asignacion_curso_maestro.maestro_id')
            ->where('asignacion_curso_maestro.grado_seccion_id', $gradoSeccionId)
            ->where('maestros.usuario_id', (int) ($usuario['id_usuario'] ?? 0))
            ->countAllResults() > 0;
    }

    private function puedeConsultarAlumno(int $alumnoId, array $usuario): bool
    {
        $rolId = (int) ($usuario['rol_id'] ?? 0);
        if (in_array($rolId, [1, 2, 3], true)) {
            return true;
        }
        if ($rolId === 5) {
            return model(AlumnosModel::class, false)
                ->perteneceAUsuario($alumnoId, (int) ($usuario['id_usuario'] ?? 0));
        }
        if ($rolId === 4) {
            return model(AsignacionCursoMaestroModel::class, false)
                ->maestroTieneAlumno($alumnoId, (int) ($usuario['id_usuario'] ?? 0));
        }

        return false;
    }
}
