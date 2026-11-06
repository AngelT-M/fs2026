<?php

namespace App\Models;

use CodeIgniter\Model;

class NivelesEducativosModel extends Model
{
    protected $table = 'niveles_educativos';
    protected $primaryKey = 'nivel_id';
    protected $returnType = 'array';
    protected $allowedFields = ['nom_nivel'];

    public function listarApi(): array
    {
        return $this->orderBy($this->primaryKey)->findAll(500);
    }
}
