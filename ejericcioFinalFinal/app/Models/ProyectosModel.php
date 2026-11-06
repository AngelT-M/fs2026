<?php

namespace App\Models;

use CodeIgniter\Model;

class ProyectosModel extends Model
{
    protected $table            = 'proyectos';
    protected $primaryKey       = 'id_proyecto';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'id_usuario'
    ];

    private function formatear(array $fila): array{
        foreach(['id_proyecto', 'id_usuario'] as $campo){
            if (isset($fila[$campo])) {
                $fila[$campo] = (int) $fila[$campo];
            }
        }
        return $fila;
    }

    public function mostrar():array{
        $datos = $this->select('
            proyectos.id_proyecto,
            proyectos.nombre as proyecto,
            proyectos.descripcion,
            proyectos.fecha_inicio,
            proyectos.fecha_fin,
            proyectos.estado,
            usuario.nombre as nombre
        ')
        ->join('usuario','usuario.id_usuario = proyectos.id_usuario')
        ->findAll();
        return array_map([$this, 'formatear'], $datos);
    }

    public function buscar(int $id): ?array
    {
        $datos = $this->find($id);
        return $datos !== null ? $this->formatear($datos): null;
    }


    public function crear(array $datos): int 
    {
        $valor = $this->insert([
            'nombre' =>$datos['nombre'],
            'descripcion' =>$datos['descripcion'],
            'fecha_inicio' =>$datos['fecha_inicio'],
            'fecha_fin' =>$datos['fecha_fin'],
            'estado' =>$datos['estado'],
            'id_usuario' =>$datos['id_usuario']
        ]);
        return $valor === false  ? null : (int) $valor;
    }

    public function actualizar(int $id, array $datos):bool 
    {
        $valor = array_intersect_key($datos, array_flip($this->allowedFields));
        if (! $valor) {
            return True;
        }
        return $this->update($id, $valor);
    }

    public function eliminar(int $id): bool 
    {
        return $this->delete($id);
    }

    




}
