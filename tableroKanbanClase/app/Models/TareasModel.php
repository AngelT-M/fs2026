<?php

namespace App\Models;

use CodeIgniter\Model;

class TareasModel extends Model
{
    protected $table            = 'tareas';
    protected $primaryKey       = 'tarea_id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'titulo',
        'descripcion',
        'categoria_id',
        'usuario_id',
        'prioridad',
        'estado',
        'orden',
        'fecha_creacion'
    ];

    private function formatear(array $fila): array
    {
        foreach(['tarea_id','categoria_id','usuario_id','orden']as $campo){
            if (isset($fila[$campo])) {
                $fila[$campo] = (int)  $fila[$campo];
            }
        }
        return  $fila;
    }


    public function mostrar(): array
    {
        $datos = $this->select('
            tareas.tarea_id,
            tareas.titulo,
            tareas.descripcion,
            tareas.categoria_id,
            categorias.nom_categoria,
            tareas.usuario_id,
            usuarios.nom_usuario,
            tareas.prioridad,
            tareas.estado,
            tareas.orden,
            tareas.fecha_creacion
        ')
        ->join('categorias', 'categorias.categoria_id = tareas.categoria_id')
        ->join('usuarios', 'usuarios.usuario_id = tareas.usuario_id')
        ->orderBy('tareas.orden', 'ASC')
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
            'titulo' =>$datos['titulo'],
            'descripcion' =>$datos['descripcion'],
            'categoria_id'=>$datos['categoria_id'],
            'usuario_id' =>$datos['usuario_id'], 
            'prioridad' =>$datos['prioridad'],
            'orden' =>$datos['orden'],
            'fecha_creacion' =>$datos['fecha_creacion']
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
