<?php

namespace App\Models;

use CodeIgniter\Model;

class TareaModel extends Model
{
   protected $table            = 'tarea';
    protected $primaryKey       = 'id_tarea';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'titulo',
        'descripcion',
        'id_proyecto',
        'id_categoria',
        'prioridad',
        'estado',
        'fecha_limite'
    ];

    private function formatear(array $fila ):array
    {
        foreach (['id_tareas','id_categoria','id_proyecto'] as $campo) {
            if (isset($fila[$campo])) {
                $fila[$campo] = $fila[$campo];
            }
        }
        return $fila;
    }

    public function mostrar(int $idProyecto): array
    {
        $datos = $this->select('
            tarea.titulo,
            tarea.descripcion,
            proyectos.nombre as proyecto,
            categoria.nombre as categoria,
            tarea.prioridad,
            tarea.estado,
            tarea.fecha_limite
        ')
        ->join('proyectos','proyectos.id_proyecto = tarea.id_proyecto')
        ->join('categoria','categoria.id_categoria = tarea.id_categoria')
        ->where('tarea.id_proyecto', $idProyecto)
        ->findAll();
        return array_map([$this, 'formatear'], $datos);
    }

    public function buscar(int $idProyecto, int $id): ?array
    {
        $datos = $this->where('id_proyecto', $idProyecto)
                    ->where('id_tarea', $id)
                    ->first();
        return $datos !== null ? $this->formatear($datos): null;
    }

    public function crear(int $idProyecto, array $datos): ?int
    {
        $datos['id_proyecto'] = $idProyecto;

        $id = $this->insert($datos);
        return $id === false ? null : (int) $id;
    }

    public function actualizar(int $idProyecto, int $id, array $datos): ?bool
    {
        if ($this->buscar($idProyecto, $id) === null) {
            return null;
        }
        unset($datos['id_tarea'], $datos['id_proyecto']);
        return $this->update($id, $datos);
    }

    public function eliminar(int $idProyecto,int $id){
        if ($this->buscar($idProyecto, $id) === null) {
            return null;
        }
        return $this->delete($id);
    }

}
