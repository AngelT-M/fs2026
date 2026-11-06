<?php

namespace App\Models;

use CodeIgniter\Model;

class CursosModel extends Model
{
    protected $table = 'cursos';
    protected $primaryKey = 'curso_id';
    protected $returnType = 'array';
    protected $allowedFields = ['nom_curso', 'descripcion'];

    public function listarApi(): array
    {
        return $this->orderBy($this->primaryKey)->findAll(500);
    }

    public function crearApi(array $datos): array|false
    {
        $id = $this->insert($datos, true);
        return $id === false ? false : $this->find($id);
    }

    public function actualizarApi(int $id, array $datos): array|false|null
    {
        if ($this->find($id) === null) {
            return null;
        }

        return $this->update($id, $datos) ? $this->find($id) : false;
    }
}
