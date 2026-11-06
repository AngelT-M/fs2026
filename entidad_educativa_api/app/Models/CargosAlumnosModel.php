<?php

namespace App\Models;

use CodeIgniter\Model;

class CargosAlumnosModel extends Model
{
    protected $table = 'cargos_alumnos';
    protected $primaryKey = 'cargo_id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'asignacion_grado_id',
        'pago_id',
        'monto_cobrar',
        'fecha_vencimiento',
        'estado',
    ];

    public function cargosAlumno(int $alumnoId, array $usuario): array|false
    {
        if ((int) ($usuario['rol_id'] ?? 0) === 5) {
            $propietario = model(AlumnosModel::class, false)
                ->where('alumno_id', $alumnoId)
                ->where('usuario_id', (int) ($usuario['id_usuario'] ?? 0))
                ->countAllResults();
            if ($propietario === 0) {
                return false;
            }
        }

        return $this->db->table('cargos_alumnos ca')
            ->select(
                'ca.cargo_id, ca.monto_cobrar, ca.fecha_vencimiento, ca.estado, pagos.descripcion, pagos.cantidad_pagos, '
                . 'GREATEST(ca.monto_cobrar - COALESCE((SELECT SUM(pr.monto_pagado) FROM pagos_realizados pr '
                . 'WHERE pr.cargo_id = ca.cargo_id), 0), 0) AS saldo_pendiente',
                false
            )
            ->join('asignacion_grados', 'asignacion_grados.asignacion_grado_id = ca.asignacion_grado_id')
            ->join('pagos', 'pagos.pago_id = ca.pago_id')
            ->where('asignacion_grados.alumno_id', $alumnoId)
            ->orderBy('ca.fecha_vencimiento')
            ->get()
            ->getResultArray();
    }

    public function procesarPago(int $cargoId, int $usuarioId, float $monto): array
    {
        $personal = model(PersonalAdministrativosModel::class, false)
            ->select('personal_id')
            ->where('usuario_id', $usuarioId)
            ->first();
        if ($personal === null) {
            return ['status' => 403, 'message' => 'La cuenta no está asociada a personal autorizado para cobrar.', 'data' => null];
        }

        $this->db->transStart();
        $consulta = $this->db->table($this->table)
            ->where($this->primaryKey, $cargoId)
            ->getCompiledSelect();
        if ($this->db->DBDriver !== 'SQLite3') {
            $consulta .= ' FOR UPDATE';
        }
        $cargo = $this->db->query($consulta)->getRowArray();
        if ($cargo === null) {
            $this->db->transRollback();
            return ['status' => 404, 'message' => 'No se encontró el cargo.', 'data' => null];
        }
        if ($cargo['estado'] === 'PAGO_TOTAL') {
            $this->db->transRollback();
            return ['status' => 409, 'message' => 'El cargo ya está pagado.', 'data' => null];
        }

        $pagos = model(PagosRealizadosModel::class, false);
        $pagado = $pagos->selectSum('monto_pagado', 'total')
            ->where('cargo_id', $cargoId)
            ->first();
        $pendiente = round((float) $cargo['monto_cobrar'] - (float) ($pagado['total'] ?? 0), 2);
        $monto = round($monto, 2);
        if ($monto > $pendiente) {
            $this->db->transRollback();
            return ['status' => 422, 'message' => 'El monto supera el saldo pendiente del cargo.', 'data' => null];
        }

        $pagoId = $pagos->insert([
            'personal_id' => $personal['personal_id'],
            'cargo_id' => $cargoId,
            'monto_pagado' => $monto,
            'fecha_pago' => date('Y-m-d H:i:s'),
        ], true);
        $estado = $monto === $pendiente ? 'PAGO_TOTAL' : 'PAGO_PARCIAL';
        $this->update($cargoId, ['estado' => $estado]);
        $this->db->transComplete();

        if ($pagoId === false || ! $this->db->transStatus()) {
            return ['status' => 409, 'message' => 'No fue posible registrar el pago.', 'data' => null];
        }

        return [
            'status' => 201,
            'message' => 'Pago registrado.',
            'data' => [
                'pago_realizado_id' => $pagoId,
                'cargo_id' => $cargoId,
                'monto_pagado' => $monto,
                'saldo_pendiente' => round($pendiente - $monto, 2),
                'estado' => $estado,
            ],
        ];
    }

    public function generarCargosPorGrado(int $pagoId, int $gradoId, string $fechaVencimiento): array
    {
        $this->db->transStart();
        $pago = $this->db->table('pagos')
            ->where('pago_id', $pagoId)
            ->where('grado_id', $gradoId)
            ->get()
            ->getRowArray();
        if ($pago === null) {
            $this->db->transRollback();
            return ['status' => 404, 'message' => 'No se encontró el pago para el grado indicado.', 'data' => null];
        }
        if ((float) $pago['monto_base'] <= 0) {
            $this->db->transRollback();
            return ['status' => 422, 'message' => 'El monto configurado para el pago no es válido.', 'data' => null];
        }

        $ciclo = (int) date('Y');
        $asignaciones = $this->db->table('asignacion_grados ag')
            ->select('ag.asignacion_grado_id')
            ->join('grados_secciones gs', 'gs.grado_seccion_id = ag.grado_seccion_id')
            ->join('alumnos al', 'al.alumno_id = ag.alumno_id')
            ->join('usuarios u', 'u.usuario_id = al.usuario_id')
            ->where('gs.grado_id', $gradoId)
            ->where('gs.ciclo', $ciclo)
            ->where('u.estado', 'ACTIVO')
            ->get()
            ->getResultArray();
        if ($asignaciones === []) {
            $this->db->transRollback();
            return ['status' => 404, 'message' => 'No hay alumnos activos asignados al grado en el ciclo actual.', 'data' => null];
        }

        $existentes = $this->select('asignacion_grado_id')
            ->where('pago_id', $pagoId)
            ->whereIn('asignacion_grado_id', array_column($asignaciones, 'asignacion_grado_id'))
            ->findAll();
        $asignacionesExistentes = array_fill_keys(
            array_map('intval', array_column($existentes, 'asignacion_grado_id')),
            true
        );
        $nuevos = [];
        foreach ($asignaciones as $asignacion) {
            $asignacionId = (int) $asignacion['asignacion_grado_id'];
            if (! isset($asignacionesExistentes[$asignacionId])) {
                $nuevos[] = [
                    'asignacion_grado_id' => $asignacionId,
                    'pago_id' => $pagoId,
                    'monto_cobrar' => $pago['monto_base'],
                    'fecha_vencimiento' => $fechaVencimiento,
                    'estado' => 'PENDIENTE',
                ];
            }
        }

        $insertado = $nuevos === [] || $this->insertBatch($nuevos) !== false;
        $this->db->transComplete();
        if (! $insertado || ! $this->db->transStatus()) {
            return ['status' => 500, 'message' => 'No fue posible generar los cargos.', 'data' => null];
        }

        return [
            'status' => 201,
            'message' => 'Generación de cargos completada.',
            'data' => ['creados' => count($nuevos), 'omitidos' => count($asignacionesExistentes)],
        ];
    }
}
