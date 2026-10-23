<?php

namespace App\Models;

use CodeIgniter\Model;

class TareasModel extends Model
{
    protected $table            = 'tareas';
    protected $primaryKey       = 'tarea_id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'titulo'
    ];

    private function formatear(array $fila):array{
        foreach(['tarea_id'] as $campo){
            if (isset($fila[$campo])) {
                $fila[$campo] = (int) $fila[$campo];
            }
        }
        return $fila;
    }

    public function mostrar(): array{
        $datos = $this->select('
            tarea_id,
            titulo
        ')
        ->findAll();
        return array_map([$this, 'formatear'], $datos);
    }

    public function buscar(int $id): ?array{
        $datos = $this->find($id);
        return $datos ? $this->formatear($datos): null;
    }

    public function crear(array $datos): ?int
    {
        $id = $this->insert([
            'titulo' =>$datos['titulo']
        ]);
        return $id === false ? null: (int) $id;
    }

    public function actualizar(int $id, array $datos): bool
    {
        $valores = array_intersect_key($datos, array_flip($this->allowedFields));
        if (! $valores) {
            return true;
        }
        return $this->update($id, $valores);
    }


    public function eliminar(int $id): bool{
        return $this->delete($id);
    }

    



}
