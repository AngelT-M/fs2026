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
        return $this->orderBy($this->primaryKey)->findAll(500);
    }

    public function guardarApi(array $datos): int|false
    {
        return $this->insert($datos, true);
    }

    public function buscarAula(int $id): ?array
    {
        return $this->find($id);
    }
}
