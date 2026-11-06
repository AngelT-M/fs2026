<?php

namespace App\Models;

use CodeIgniter\Model;

class PagosRealizadosModel extends Model
{
    protected $table = 'pagos_realizados';
    protected $primaryKey = 'pago_realizado_id';
    protected $returnType = 'array';
    protected $allowedFields = ['personal_id', 'cargo_id', 'monto_pagado', 'fecha_pago'];

    public function obtenerComprobante(int $pagoRealizadoId): ?array
    {
        return $this->db->table('pagos_realizados pr')
            ->select('pr.pago_realizado_id, pr.monto_pagado, pr.fecha_pago, ca.cargo_id, ca.monto_cobrar, ca.fecha_vencimiento, p.descripcion AS concepto, a.alumno_id, a.carne, a.nombre AS alumno_nombre, a.apellido AS alumno_apellido, a.nom_encargado, a.tel_encargado, pa.personal_id, pa.nombre AS cobrador_nombre, pa.apellido AS cobrador_apellido')
            ->join('cargos_alumnos ca', 'ca.cargo_id = pr.cargo_id')
            ->join('pagos p', 'p.pago_id = ca.pago_id')
            ->join('asignacion_grados ag', 'ag.asignacion_grado_id = ca.asignacion_grado_id')
            ->join('alumnos a', 'a.alumno_id = ag.alumno_id')
            ->join('personal_administrativos pa', 'pa.personal_id = pr.personal_id')
            ->where('pr.pago_realizado_id', $pagoRealizadoId)
            ->get()
            ->getRowArray();
    }
}
