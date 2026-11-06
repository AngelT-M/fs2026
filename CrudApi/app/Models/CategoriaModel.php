<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoriaModel extends Model
{
    protected $table            = 'categoria';
    protected $primaryKey       = 'id_categoria';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'nombre',
        'descripcion',
        'estado'
    ];


    private function formatear(array $fila): array
    {
        foreach(['id_categoria'] as $campo){
            if (isset($fila[$campo])) {
                $fila[$campo] = (int) $fila[$campo];
            }
        }
        return $fila;
    }

    public function mostrar( ): array
    {
        $datos = $this->select('
            id_categoria,
            nombre,
            descripcion,
            estado
        ')
        ->findAll();
        return array_map([$this, 'formatear'], $datos);
    }

    public function buscar(int $id):?array
    {
        $dato = $this->find($id);
        return $dato ? $this->formatear($dato): null;
    }

    public function crear(array $datos): int 
    {
        $id = $this->insert([
            'nombre' => $dato['nombre'],
            'descripcion' =>$dato['descripcion'],
            'estado' =>$dato['estado']
        ]);
        return $id === false ? null : (int) $id;
    }

    public function actualizar(int $id, array $datos): bool 
    {
        $valor = array_intersect_key($dato, array_flip($this->allowedFields));
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
