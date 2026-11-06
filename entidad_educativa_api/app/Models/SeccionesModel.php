<?php

namespace App\Models;

use CodeIgniter\Model;

class SeccionesModel extends Model
{
    protected $table = 'secciones';
    protected $primaryKey = 'seccion_id';
    protected $returnType = 'array';
    protected $allowedFields = ['nom_seccion'];

    public function listarApi(): array
    {
        return $this->orderBy($this->primaryKey)->findAll(500);
    }
}
