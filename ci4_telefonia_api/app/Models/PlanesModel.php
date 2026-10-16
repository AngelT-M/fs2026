<?php

namespace App\Models;

use CodeIgniter\Model;

class PlanesModel extends Model
{
    protected $table='planes';
    protected $primaryKey = 'plan_id';

    protected $allowedFields =['nombre','pago_mensual','cantidad_minutos','cantidad_mensajes'];

    protected $validationRules=[
        'nombre'=>'required',
        'pago_mensual'=>'required|numeric',
        'cantidad_minutos'=>'required|integer',
        'cantidad_mensajes'=>'required|integer'
    ];

}