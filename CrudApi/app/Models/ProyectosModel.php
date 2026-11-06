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

    private function formatear(array $fila ):array
    {
        foreach(['id_categoria','id_usuario'] as $campo){
            if (isset($fila[$campo])) {
                $fila[$campo] = (int) $fila[$campo];
            }
        }
        return $fila;
    }

    public function mostrar():array
    {
        $datos =  $this->select('
                proyectos.id_proyecto,
                proyectos.nombre,
                proyectos.descripcion,
                proyectos.fecha_inicio,
                proyectos.fecha_fin,
                proyectos.estado,
                usuario.nombre
            ')
            ->join('usuario', 'usuario.id_usuario = proyectos.id_usuario')
            ->findAll();
        return array_map([$this, 'formatear'], $datos);
    }



    public function buscar(int $id): ?array{
        $datos = $this->find($id);
        return $datos ? $this->formatear($datos): null;
    }

    public function crear(array $datos): int 
    {
        $datos = $this->insert([
            'nombre' =>$datos['nombre'],
            'descripcion' =>$datos['descripcion'],
            'fecha_incio' =>$datos['fecha_inicio'],
            'fecha_fin' =>$datos['fecha_fin'],
            'estado' =>$datos['estado'],
            'id_usuario' =>$datos['id_usuario']
        ]);
        return $datos === false ? null : (int) $datos;
    }

    public function actualizar(int $id, array $datos):bool 
    {
        $datos = array_intersect_key($datos, array_flip($this->allowedFields));
        if (! $datos) {
            return true;
        }
        return $this->update($id, $datos);
    }

    public function eliminar(int $id): void 
    {
        $this->delete($id);
    }





}
