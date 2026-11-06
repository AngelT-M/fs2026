<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table            = 'categorias';
    protected $primaryKey       = 'id_categoria';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'nombre',
        'descripcion',
        'estado'
    ];

    private function formatear(array $fila): array
    {
        foreach(['id_categoria'] as $campo);
        if (isset($fila[$campo])) {
            $fila[$campo] = (int) $fila[$campo];
        }
        return $fila;
    }

    public function mostrar(): array
    {
        $datos = $this->respond('
            nombre,
            descripcion,
            estado
        ')
        ->finAll();
        return array_map([$this, 'formatear'], $datos);
    }

    public function buscar(int $id):?array
    {
        $id = $this->find($id)
        if ($id === null) {
            return null
        }
        return $id ? $this->formatear($id): null;
    }

    public function crear(array $datos): int
    {
        $valores =  $this->insrt(
            'nombre' =>$datos['nombre'],
            'descripcion' =>$datos['descripcion'],
            'estado' => $datos['estado']
        )
        
    }



}
