<?php

namespace App\Models;

use CodeIgniter\Model;

class AsignacionCursoMaestroModel extends Model
{
    protected $table = 'asignacion_curso_maestro';
    protected $primaryKey = 'asignacion_curso_maestro_id';
    protected $returnType = 'array';
    protected $allowedFields = ['maestro_id', 'grado_seccion_id', 'curso_id'];

    public function existeAsignacion(int $maestroId, int $gradoSeccionId, int $cursoId): bool
    {
        return $this->where('maestro_id', $maestroId)
            ->where('grado_seccion_id', $gradoSeccionId)
            ->where('curso_id', $cursoId)
            ->countAllResults() > 0;
    }

    public function maestroPuedeGestionar(int $asignacionId, int $usuarioId): bool
    {
        $maestro = model(MaestrosModel::class, false)
            ->select('maestro_id')
            ->where('usuario_id', $usuarioId)
            ->first();

        return $maestro !== null && $this->where('asignacion_curso_maestro_id', $asignacionId)
            ->where('maestro_id', $maestro['maestro_id'])
            ->countAllResults() > 0;
    }

    public function puedeConsultar(int $asignacionId, array $usuario): bool
    {
        $rolId = (int) ($usuario['rol_id'] ?? 0);
        if ($rolId === 4) {
            return $this->maestroPuedeGestionar($asignacionId, (int) ($usuario['id_usuario'] ?? 0));
        }
        if ($rolId !== 5) {
            return $this->find($asignacionId) !== null;
        }

        return $this->join('asignacion_grados', 'asignacion_grados.grado_seccion_id = asignacion_curso_maestro.grado_seccion_id')
            ->join('alumnos', 'alumnos.alumno_id = asignacion_grados.alumno_id')
            ->where('asignacion_curso_maestro.asignacion_curso_maestro_id', $asignacionId)
            ->where('alumnos.usuario_id', (int) ($usuario['id_usuario'] ?? 0))
            ->countAllResults() > 0;
    }

    public function maestroTieneAlumno(int $alumnoId, int $usuarioId): bool
    {
        $maestro = model(MaestrosModel::class, false)
            ->select('maestro_id')
            ->where('usuario_id', $usuarioId)
            ->first();
        if ($maestro === null) {
            return false;
        }

        return $this->join('asignacion_grados', 'asignacion_grados.grado_seccion_id = asignacion_curso_maestro.grado_seccion_id')
            ->where('asignacion_curso_maestro.maestro_id', $maestro['maestro_id'])
            ->where('asignacion_grados.alumno_id', $alumnoId)
            ->countAllResults() > 0;
    }

    public function guardarApi(array $datos): int|false
    {
        return $this->insert($datos, true);
    }

    public function crearAsignacion(array $datos): array|false
    {
        $id = $this->guardarApi($datos);
        return $id === false ? false : $this->find($id);
    }

    public function listarAsignacionesParaUsuario(array $usuario): array
    {
        $builder = $this->db->table('asignacion_curso_maestro acm')
            ->select('acm.asignacion_curso_maestro_id, acm.maestro_id, m.nombre AS maestro_nombre, m.apellido AS maestro_apellido, m.carne_maestro, acm.curso_id, c.nom_curso, acm.grado_seccion_id, g.nom_grado, s.nom_seccion, gs.ciclo')
            ->join('maestros m', 'm.maestro_id = acm.maestro_id')
            ->join('cursos c', 'c.curso_id = acm.curso_id')
            ->join('grados_secciones gs', 'gs.grado_seccion_id = acm.grado_seccion_id')
            ->join('grados g', 'g.grado_id = gs.grado_id')
            ->join('secciones s', 's.seccion_id = gs.seccion_id');

        if ((int) ($usuario['rol_id'] ?? 0) === 4) {
            $builder->where('m.usuario_id', (int) ($usuario['id_usuario'] ?? 0));
        } elseif ((int) ($usuario['rol_id'] ?? 0) === 5) {
            $builder->join(
                'asignacion_grados ag',
                'ag.grado_seccion_id = acm.grado_seccion_id'
            )
                ->join('alumnos al', 'al.alumno_id = ag.alumno_id')
                ->where('al.usuario_id', (int) ($usuario['id_usuario'] ?? 0))
                ->distinct();
        }

        return $builder->orderBy('g.grado_id', 'ASC')
            ->orderBy('s.nom_seccion', 'ASC')
            ->orderBy('c.nom_curso', 'ASC')
            ->get()
            ->getResultArray();
    }
}
