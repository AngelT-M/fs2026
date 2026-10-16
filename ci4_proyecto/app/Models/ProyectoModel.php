<?php

namespace App\models;

use CodeIgniter\Model;

class ProyectoModel extends Model
{
    protected $table = 'proyecto';
    protected $primaryKey = 'id_proyecto';
    protected $allowedField = [
        'id_proyecto',
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_final',
        'estado',
        'id_usuario'
    ];
}
