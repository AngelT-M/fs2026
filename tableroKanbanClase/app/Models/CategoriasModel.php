<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriasModel extends Model
{
    protected $table            = 'categorias';
    protected $primaryKey       = 'categoria_id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'nom_categoria'
    ];
    
    private function formatear(array $fila): array
    {
        foreach(['categoria_id']as $campo){
            if (isset($fila[$campo])) {
                $fila[$campo] = (int)  $fila[$campo];
            }
        }
        return  $fila;
    }


    public function mostrar(): array
    {
        $datos = $this->select('
            categoria_id,
            nom_categoria
        ')
        ->findAll();
        return array_map([$this,'formatear'], $datos);
    }

    public function buscar(int $id): ?array
    {
        $datos = $this->find($id);
        return $datos ? $this->formatear($datos): null;
    }

    public function crear(array $datos): int 
    {
        $id = $this->insert([
            'nom_categoria' =>$datos['nom_categoria'],
        ]);
        return $id === false ? null : (int) $id;
    }


    public function actualizar(int $id, array $datos): bool 
    {
        $valor = array_intersect_key($datos, array_flip($this->allowedFields));
        if (! $valor) {
            return true;
        }
        return $this->update($id, $valor);
    }

    public function eliminar(int $id): void
    {
        $this->delete($id);
    }



}
