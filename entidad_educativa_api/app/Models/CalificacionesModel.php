<?php

namespace App\Models;

use CodeIgniter\Model;

class CalificacionesModel extends Model
{
    protected $table = 'calificaciones';
    protected $primaryKey = 'calificacion_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'asignacion_grado_id',
        'actividad_curso_id',
        'punteo_obtenido',
        'fecha_registro',
    ];

    public function guardarCalificaciones(array $items, array $usuario = []): array|false
    {
        $this->db->transStart();
        $guardadas = [];
        foreach ($items as $item) {
            $actividad = $this->db->table('actividades_curso')
                ->where('actividad_curso_id', $item['actividad_curso_id'])
                ->get()
                ->getRowArray();
            $asignacion = model(AsignacionGradosModel::class, false)->find($item['asignacion_grado_id']);
            if ($actividad === null || $asignacion === null) {
                $this->db->transRollback();
                return false;
            }
            $cursoMaestro = model(AsignacionCursoMaestroModel::class, false)
                ->where('asignacion_curso_maestro_id', $actividad['asignacion_curso_maestro_id'])
                ->first();
            if (
                $cursoMaestro === null
                || (int) $asignacion['grado_seccion_id'] !== (int) $cursoMaestro['grado_seccion_id']
                || (float) $item['punteo_obtenido'] > (float) $actividad['punteo_maximo']
            ) {
                $this->db->transRollback();
                return false;
            }
            if (
                (int) ($usuario['rol_id'] ?? 0) === 4
                && ! model(AsignacionCursoMaestroModel::class, false)
                    ->maestroPuedeGestionar(
                        (int) $cursoMaestro['asignacion_curso_maestro_id'],
                        (int) ($usuario['id_usuario'] ?? 0)
                    )
            ) {
                $this->db->transRollback();
                return false;
            }

            $values = [
                'asignacion_grado_id' => (int) $item['asignacion_grado_id'],
                'actividad_curso_id' => (int) $item['actividad_curso_id'],
                'punteo_obtenido' => $item['punteo_obtenido'],
                'fecha_registro' => date('Y-m-d'),
            ];
            $existing = $this->where('asignacion_grado_id', $values['asignacion_grado_id'])
                ->where('actividad_curso_id', $values['actividad_curso_id'])
                ->first();
            if ($existing === null) {
                $values['calificacion_id'] = $this->insert($values, true);
            } else {
                $this->update($existing['calificacion_id'], $values);
                $values['calificacion_id'] = (int) $existing['calificacion_id'];
            }
            $guardadas[] = $values;
        }

        $this->db->transComplete();

        return $this->db->transStatus() ? $guardadas : false;
    }

    public function obtenerBoleta(int $alumnoId): array
    {
        return $this->select('calificaciones.calificacion_id, calificaciones.punteo_obtenido, calificaciones.fecha_registro, actividades_curso.bimestre, actividades_curso.nom_actividad, actividades_curso.punteo_maximo, cursos.nom_curso')
            ->join('actividades_curso', 'actividades_curso.actividad_curso_id = calificaciones.actividad_curso_id')
            ->join('asignacion_grados', 'asignacion_grados.asignacion_grado_id = calificaciones.asignacion_grado_id')
            ->join('asignacion_curso_maestro', 'asignacion_curso_maestro.asignacion_curso_maestro_id = actividades_curso.asignacion_curso_maestro_id')
            ->join('cursos', 'cursos.curso_id = asignacion_curso_maestro.curso_id')
            ->where('asignacion_grados.alumno_id', $alumnoId)
            ->orderBy('actividades_curso.bimestre')
            ->orderBy('cursos.nom_curso')
            ->findAll();
    }

    public function listarPorActividad(int $actividadId, array $usuario = []): array|false
    {
        $actividad = $this->db->table('actividades_curso ac')
            ->select('ac.asignacion_curso_maestro_id, acm.grado_seccion_id')
            ->join('asignacion_curso_maestro acm', 'acm.asignacion_curso_maestro_id = ac.asignacion_curso_maestro_id')
            ->where('ac.actividad_curso_id', $actividadId)
            ->get()
            ->getRowArray();

        if ($actividad === null) {
            return false;
        }

        if ((int) ($usuario['rol_id'] ?? 0) === 4
            && ! model(AsignacionCursoMaestroModel::class, false)
                ->maestroPuedeGestionar(
                    (int) $actividad['asignacion_curso_maestro_id'],
                    (int) ($usuario['id_usuario'] ?? 0)
                )) {
            return false;
        }

        return $this->db->table('asignacion_grados ag')
            ->select('ag.asignacion_grado_id, al.alumno_id, al.carne, al.nombre, al.apellido, c.calificacion_id, c.punteo_obtenido, c.fecha_registro')
            ->join('alumnos al', 'al.alumno_id = ag.alumno_id')
            ->join('usuarios u', 'u.usuario_id = al.usuario_id')
            ->join(
                'calificaciones c',
                'c.asignacion_grado_id = ag.asignacion_grado_id AND c.actividad_curso_id = ' . $this->db->escape($actividadId),
                'left'
            )
            ->where('ag.grado_seccion_id', (int) $actividad['grado_seccion_id'])
            ->where('u.estado', 'ACTIVO')
            ->orderBy('al.apellido')
            ->orderBy('al.nombre')
            ->get()
            ->getResultArray();
    }
}
