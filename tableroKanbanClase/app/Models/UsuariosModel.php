<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuariosModel extends Model
{
    protected $table            = 'usuarios';
    protected $primaryKey       = 'usuario_id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'nom_usuario',
        'fecha_nac',
        'numero',
        'email'
    ];

    private function formatear(array $fila): array
    {
        foreach(['usuario_id']as $campo){
            if (isset($fila[$campo])) {
                $fila[$campo] = (int)  $fila[$campo];
            }
        }
        return  $fila;
    }


    public function mostrar(): array
    {
        $datos = $this->select('
            usuario_id,
            nom_usuario,
            fecha_nac,
            numero,
            email
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
            'nom_usuario' =>$datos['nom_usuario'],
            'fecha_nac' =>$datos['fecha_nac'],
            'numero'=>$datos['numero'],
            'email' =>$datos['email']
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
