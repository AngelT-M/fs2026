<?php

namespace App\Models;

use CodeIgniter\Model;

class TareaModel extends Model
{
    protected $table = 'tareas';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'proyecto_id',
        'categoria_id',
        'asignado_a',
        'titulo',
        'descripcion',
        'estado',
        'prioridad',
        'fecha_vencimiento',
        'orden',
    ];
}
