<?php

namespace App\Models;

use CodeIgniter\Model;

class AsignacionGradosModel extends Model
{
    protected $table = 'asignacion_grados';
    protected $primaryKey = 'asignacion_grado_id';
    protected $returnType = 'array';
    protected $allowedFields = ['alumno_id', 'grado_seccion_id'];

    public function existeAsignacion(int $alumnoId, int $gradoSeccionId): bool
    {
        return $this->where('alumno_id', $alumnoId)
            ->where('grado_seccion_id', $gradoSeccionId)
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

    public function alumnoEnAula(int $alumnoId, int $asignacionGradoId): ?array
    {
        return $this->select('asignacion_grados.asignacion_grado_id, asignacion_grados.grado_seccion_id, alumnos.usuario_id')
            ->join('alumnos', 'alumnos.alumno_id = asignacion_grados.alumno_id')
            ->where('asignacion_grados.asignacion_grado_id', $asignacionGradoId)
            ->where('asignacion_grados.alumno_id', $alumnoId)
            ->first();
    }

    public function aulaDeAlumno(int $alumnoId): ?array
    {
        return $this->select('grados.nivel_id')
            ->join('grados_secciones', 'grados_secciones.grado_seccion_id = asignacion_grados.grado_seccion_id')
            ->join('grados', 'grados.grado_id = grados_secciones.grado_id')
            ->where('asignacion_grados.alumno_id', $alumnoId)
            ->first();
    }
}
