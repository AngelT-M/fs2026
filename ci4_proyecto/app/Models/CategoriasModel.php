<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriasModel extends Model 
{
    protected $table = 'categoria';
    protected $primaryKey = 'id_categoria';
    protected $allowedFields = [
        'id_categoria',
        'nombre',
        'descripcion',
        'estado'
    ];
}