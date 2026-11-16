<?php

namespace App\Models;

use CodeIgniter\Model;

class BimestresModel extends Model
{
    public const PERIODOS = [
        ['nombre' => '1er Bimestre', 'orden' => 1],
        ['nombre' => '2do Bimestre', 'orden' => 2],
        ['nombre' => '3er Bimestre', 'orden' => 3],
        ['nombre' => '4to Bimestre', 'orden' => 4],
        ['nombre' => 'Recuperacion', 'orden' => 5],
    ];

    protected $table = 'bimestres';
    protected $primaryKey = 'bimestre_id';
    protected $returnType = 'array';
    protected $allowedFields = ['ciclo', 'nombre', 'orden', 'fecha_inicio', 'fecha_fin'];

    public function listarApi(?int $ciclo = null): array
    {
        $builder = $this->orderBy('ciclo', 'DESC')->orderBy('orden', 'ASC');
        if ($ciclo !== null) {
            $builder->where('ciclo', $ciclo);
        }

        return $builder->findAll();
    }

    public function crearCiclo(int $ciclo): array|false
    {
        if ($this->where('ciclo', $ciclo)->countAllResults() > 0) {
            return false;
        }

        $periodos = array_map(
            static fn (array $periodo): array => $periodo + [
                'ciclo' => $ciclo,
                'fecha_inicio' => null,
                'fecha_fin' => null,
            ],
            self::PERIODOS
        );

        $this->db->transBegin();
        if (! $this->insertBatch($periodos)) {
            $this->db->transRollback();
            return false;
        }
        $this->db->transComplete();

        return $this->db->transStatus() ? $this->listarApi($ciclo) : false;
    }

    public function perteneceAAsignacion(int $bimestreId, int $asignacionId): bool
    {
        return $this->db->table('asignacion_curso_maestro acm')
            ->join('grados_secciones gs', 'gs.grado_seccion_id = acm.grado_seccion_id')
            ->join('bimestres b', 'b.ciclo = gs.ciclo')
            ->where('b.bimestre_id', $bimestreId)
            ->where('acm.asignacion_curso_maestro_id', $asignacionId)
            ->countAllResults() > 0;
    }

    public function actualizarFechas(int $id, ?string $fechaInicio, ?string $fechaFin): array|false|null
    {
        $bimestre = $this->find($id);
        if ($bimestre === null) {
            return null;
        }
        if (! $this->update($id, [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
        ])) {
            return false;
        }

        return $this->find($id);
    }

    public function fechasSeSuperponen(int $id, string $fechaInicio, string $fechaFin): bool
    {
        $bimestre = $this->find($id);
        if ($bimestre === null) {
            return false;
        }

        return $this->where('ciclo', $bimestre['ciclo'])
            ->where('bimestre_id !=', $id)
            ->where('fecha_inicio <=', $fechaFin)
            ->where('fecha_fin >=', $fechaInicio)
            ->countAllResults() > 0;
    }
}
