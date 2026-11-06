<?php

namespace App\Models;

use CodeIgniter\Model;

class GradosModel extends Model
{
    protected $table = 'grados';
    protected $primaryKey = 'grado_id';
    protected $returnType = 'array';
    protected $allowedFields = ['nom_grado', 'nivel_id'];

    public function listarApi(): array
    {
        return $this->orderBy($this->primaryKey)->findAll(500);
    }
}
