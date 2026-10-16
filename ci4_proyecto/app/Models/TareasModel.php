<?php

namespace App\models;

use CodeIgniter\Model;

class TareasModel extends Model
{
    protected $table = 'tarea';
    protected $primaryKey = 'id_tarea';
    protected $allowedFields = [
        'id_tarea',
        'titulo',
        'descripcion',
        'id_proyecto',
        'id_categoria',
        'prioridad',
        'fecha_limite'
    ];
}