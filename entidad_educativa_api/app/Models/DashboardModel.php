<?php

namespace App\Models;

use CodeIgniter\Model;

class DashboardModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'usuario_id';
    protected $returnType = 'array';
    protected $allowedFields = [];

    public function obtenerResumenGeneral(): array
    {
        $ciclo = (int) date('Y');
        $alumnos = $this->db->table('asignacion_grados ag')
            ->select('COUNT(DISTINCT ag.alumno_id) AS total', false)
            ->join('grados_secciones gs', 'gs.grado_seccion_id = ag.grado_seccion_id')
            ->join('alumnos a', 'a.alumno_id = ag.alumno_id')
            ->join('usuarios u', 'u.usuario_id = a.usuario_id')
            ->where('gs.ciclo', $ciclo)
            ->where('u.estado', 'ACTIVO')
            ->get()
            ->getRowArray();

        $docentes = $this->db->table('maestros m')
            ->select('COUNT(DISTINCT m.maestro_id) AS total', false)
            ->join('usuarios u', 'u.usuario_id = m.usuario_id')
            ->where('u.estado', 'ACTIVO')
            ->get()
            ->getRowArray();

        $inicioMes = date('Y-m-01 00:00:00');
        $inicioMesSiguiente = date('Y-m-01 00:00:00', strtotime('+1 month'));
        $recaudado = $this->db->table('pagos_realizados')
            ->selectSum('monto_pagado', 'total')
            ->where('fecha_pago >=', $inicioMes)
            ->where('fecha_pago <', $inicioMesSiguiente)
            ->get()
            ->getRowArray();

        $eventos = $this->db->table('calendario_actividades')
            ->where('fecha_inicio >=', date('Y-m-d H:i:s'))
            ->countAllResults();

        return [
            'ciclo_actual' => $ciclo,
            'alumnos_inscritos' => (int) ($alumnos['total'] ?? 0),
            'docentes_activos' => (int) ($docentes['total'] ?? 0),
            'recaudado_mes' => (float) ($recaudado['total'] ?? 0),
            'eventos_proximos' => $eventos,
        ];
    }
}
