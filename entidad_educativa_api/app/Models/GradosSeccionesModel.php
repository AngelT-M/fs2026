<?php

namespace App\Models;

use CodeIgniter\Model;

class GradosSeccionesModel extends Model
{
    protected $table = 'grados_secciones';
    protected $primaryKey = 'grado_seccion_id';
    protected $returnType = 'array';
    protected $allowedFields = ['grado_id', 'seccion_id', 'cantidad_estudiantes', 'ciclo'];

    public function listarApi(): array
    {
        return $this->select('grados_secciones.*, grados.nom_grado, niveles_educativos.nom_nivel, secciones.nom_seccion')
            ->join('grados', 'grados.grado_id = grados_secciones.grado_id', 'left')
            ->join('niveles_educativos', 'niveles_educativos.nivel_id = grados.nivel_id', 'left')
            ->join('secciones', 'secciones.seccion_id = grados_secciones.seccion_id', 'left')
            ->orderBy('grados_secciones.' . $this->primaryKey, 'ASC')
            ->findAll(500);
    }

    public function guardarApi(array $datos): int|false
    {
        return $this->insert($datos, true);
    }

    public function existeAulaEnCiclo(int $gradoId, int $seccionId, int $ciclo): bool
    {
        return $this->where('grado_id', $gradoId)
            ->where('seccion_id', $seccionId)
            ->where('ciclo', $ciclo)
            ->countAllResults() > 0;
    }

    public function tieneAsignaciones(int $id): bool
    {
        foreach (['asignacion_grados', 'asignacion_curso_maestro'] as $tabla) {
            if ($this->db->table($tabla)->where('grado_seccion_id', $id)->countAllResults() > 0) {
                return true;
            }
        }

        return false;
    }

    public function eliminarApi(int $id): bool
    {
        return $this->delete($id);
    }

    public function buscarAula(int $id): ?array
    {
        return $this->select('grados_secciones.*, grados.nom_grado, niveles_educativos.nom_nivel, secciones.nom_seccion')
            ->join('grados', 'grados.grado_id = grados_secciones.grado_id', 'left')
            ->join('niveles_educativos', 'niveles_educativos.nivel_id = grados.nivel_id', 'left')
            ->join('secciones', 'secciones.seccion_id = grados_secciones.seccion_id', 'left')
            ->where('grados_secciones.grado_seccion_id', $id)
            ->first();
    }
}
