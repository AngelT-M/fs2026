<?php

namespace App\Models;

use CodeIgniter\Model;

class GradosModel extends Model
{
    protected $table = 'grados';
    protected $primaryKey = 'grado_id';
    protected $returnType = 'array';
    protected $allowedFields = ['nom_grado', 'nivel_id'];

    public function listarApi(): array
    {
        $grados = $this->select('grados.*, niveles_educativos.nom_nivel')
            ->join('niveles_educativos', 'niveles_educativos.nivel_id = grados.nivel_id', 'left')
            ->findAll(500);

        $ordenPorGrado = [
            'Primero' => 1,
            'Segundo' => 2,
            'Tercero' => 3,
            'Cuarto' => 4,
            'Quinto' => 5,
            'Sexto' => 6,
        ];
        usort($grados, static function (array $a, array $b) use ($ordenPorGrado): int {
            $ordenNivel = (int) $a['nivel_id'] <=> (int) $b['nivel_id'];
            if ($ordenNivel !== 0) {
                return $ordenNivel;
            }

            $gradoA = $ordenPorGrado[explode(' ', trim($a['nom_grado']), 2)[0]] ?? PHP_INT_MAX;
            $gradoB = $ordenPorGrado[explode(' ', trim($b['nom_grado']), 2)[0]] ?? PHP_INT_MAX;

            return $gradoA <=> $gradoB;
        });

        return $grados;
    }
}
