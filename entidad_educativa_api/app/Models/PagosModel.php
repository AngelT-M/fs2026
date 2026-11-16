<?php

namespace App\Models;

use CodeIgniter\Model;

class PagosModel extends Model
{
    protected $table = 'pagos';
    protected $primaryKey = 'pago_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'descripcion',
        'cantidad_pagos',
        'monto_base',
        'grado_id',
    ];

    public function listarPorGrado(int $gradoId): array
    {
        return $this->select('pago_id, descripcion, cantidad_pagos, monto_base, grado_id')
            ->where('grado_id', $gradoId)
            ->orderBy($this->primaryKey, 'ASC')
            ->findAll();
    }

    public function crearApi(array $datos): array|false
    {
        $datos = array_intersect_key($datos, array_flip($this->allowedFields));
        $id = $this->insert($datos, true);

        return $id === false ? false : $this->find($id);
    }
}
