<?php

namespace App\Models;

use CodeIgniter\Model;

class ActividadesCursoModel extends Model
{
    protected $table = 'actividades_curso';
    protected $primaryKey = 'actividad_curso_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'asignacion_curso_maestro_id',
        'bimestre',
        'nom_actividad',
        'punteo_maximo',
        'descripcion',
    ];

    public function listarPorAsignacion(int $asignacionId): array
    {
        return $this->where('asignacion_curso_maestro_id', $asignacionId)
            ->orderBy($this->primaryKey)
            ->findAll();
    }

    public function crearActividad(array $datos): array|false
    {
        $id = $this->insert($datos, true);
        return $id === false ? false : $this->find($id);
    }

    public function conAsignacion(int $actividadId): ?array
    {
        return $this->find($actividadId);
    }
}
