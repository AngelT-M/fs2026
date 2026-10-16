<?php

namespace App\models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuario';
    protected $primaryKey = 'id_usuario';
    protected $allowedField = [
        'id_usuario',
        'nombre',
        'usuario',
        'correo',
        'contrasena',
        'id_rol',
        'estado'
    ];
}
