<?php

namespace App\Controllers\Api;

use App\Models\CalendarioActividadesModel;
use App\Models\CargosAlumnosModel;
use App\Models\PagosModel;
use App\Models\PagosRealizadosModel;
use CodeIgniter\HTTP\ResponseInterface;

class FinanzasController extends ApiController
{
    public function cargosAlumno(string $alumnoId): ResponseInterface
    {
        $alumnoId = $this->validarId($alumnoId);
        if ($alumnoId === null) {
            return $this->respuestaError('Identificador de alumno inválido.', 400);
        }

        $cargos = model(CargosAlumnosModel::class, false)
            ->cargosAlumno($alumnoId, $this->usuarioActual());
        return $cargos === false
            ? $this->respuestaError('No se encontró el alumno solicitado.', 404)
            : $this->respuestaExitosa($cargos);
    }

    public function realizarPago(): ResponseInterface
    {
        $datos = $this->datosEntrada();
        if ($datos === null) {
            return $this->respuestaError('El cuerpo debe ser un objeto JSON válido.', 400);
        }
        if ($error = $this->validarEntrada($datos, [
            'cargo_id' => 'required|integer|is_not_unique[cargos_alumnos.cargo_id]',
            'monto_pagado' => 'required|decimal|greater_than[0]',
        ])) {
            return $error;
        }

        $resultado = model(CargosAlumnosModel::class, false)->procesarPago(
            (int) $datos['cargo_id'],
            (int) ($this->usuarioActual()['id_usuario'] ?? 0),
            (float) $datos['monto_pagado']
        );

        return $resultado['status'] === 201
            ? $this->respuestaExitosa($resultado['data'], 201, $resultado['message'])
            : $this->respuestaError($resultado['message'], $resultado['status']);
    }

    public function generarCargosAlumnos(): ResponseInterface
    {
        $datos = $this->datosEntrada();
        if ($datos === null) {
            return $this->respuestaError('El cuerpo debe ser un objeto JSON válido.', 400);
        }
        if ($error = $this->validarEntrada($datos, [
            'pago_id' => 'required|integer|is_not_unique[pagos.pago_id]',
            'grado_id' => 'required|integer|is_not_unique[grados.grado_id]',
            'fecha_vencimiento' => 'required|valid_date[Y-m-d]',
        ])) {
            return $error;
        }

        $resultado = model(CargosAlumnosModel::class, false)->generarCargosPorGrado(
            (int) $datos['pago_id'],
            (int) $datos['grado_id'],
            $datos['fecha_vencimiento']
        );
        return $resultado['status'] === 201
            ? $this->respuestaExitosa($resultado['data'], 201, $resultado['message'])
            : $this->respuestaError($resultado['message'], $resultado['status']);
    }

    public function pagosPorGrado(string $gradoId): ResponseInterface
    {
        $gradoId = $this->validarId($gradoId);
        if ($gradoId === null) {
            return $this->respuestaError('Identificador de grado inválido.', 400);
        }

        if (db_connect()->table('grados')->where('grado_id', $gradoId)->countAllResults() === 0) {
            return $this->respuestaError('No se encontró el grado solicitado.', 404);
        }

        return $this->respuestaExitosa(
            model(PagosModel::class, false)->listarPorGrado($gradoId)
        );
    }

    public function crearPago(): ResponseInterface
    {
        $datos = $this->datosEntrada();
        if ($datos === null) {
            return $this->respuestaError('El cuerpo debe ser un objeto JSON válido.', 400);
        }
        if ($error = $this->validarEntrada($datos, [
            'descripcion' => 'required|max_length[150]',
            'cantidad_pagos' => 'required|integer|greater_than[0]',
            'monto_base' => 'required|decimal|greater_than[0]',
            'grado_id' => 'required|integer|is_not_unique[grados.grado_id]',
        ])) {
            return $error;
        }

        $pago = model(PagosModel::class, false)->crearApi($datos);
        return $pago === false
            ? $this->respuestaError('No fue posible crear la cuota.', 409)
            : $this->respuestaExitosa($pago, 201, 'Cuota creada.');
    }

    public function obtenerComprobante(string $pagoRealizadoId): ResponseInterface
    {
        $pagoRealizadoId = $this->validarId($pagoRealizadoId);
        if ($pagoRealizadoId === null) {
            return $this->respuestaError('Identificador de pago inválido.', 400);
        }

        $comprobante = model(PagosRealizadosModel::class, false)->obtenerComprobante($pagoRealizadoId);
        return $comprobante === null
            ? $this->respuestaError('No se encontró el comprobante solicitado.', 404)
            : $this->respuestaExitosa($comprobante);
    }

    public function listarComprobantes(): ResponseInterface
    {
        return $this->respuestaExitosa(
            model(PagosRealizadosModel::class, false)->listarComprobantes()
        );
    }

    public function calendario(): ResponseInterface
    {
        return $this->respuestaExitosa(
            model(CalendarioActividadesModel::class, false)->eventosParaUsuario($this->usuarioActual())
        );
    }

    public function crearEvento(): ResponseInterface
    {
        $datos = $this->datosEntrada();
        if ($datos === null) {
            return $this->respuestaError('El cuerpo debe ser un objeto JSON válido.', 400);
        }
        if ($error = $this->validarEntrada($datos, [
            'titulo' => 'required|max_length[150]',
            'descripcion' => 'permit_empty|max_length[5000]',
            'fecha_inicio' => 'required|valid_date[Y-m-d H:i:s]',
            'fecha_final' => 'required|valid_date[Y-m-d H:i:s]',
            'tipo_evento' => 'required|in_list[INSTITUCIONAL,ACADEMICO,EXAMEN,DEPORTIVO,CULTURAL]',
            'nivel_id' => 'permit_empty|integer|is_not_unique[niveles_educativos.nivel_id]',
        ])) {
            return $error;
        }

        $resultado = model(CalendarioActividadesModel::class, false)->crearEvento(
            $datos,
            (int) ($this->usuarioActual()['id_usuario'] ?? 0)
        );

        return $resultado['status'] === 201
            ? $this->respuestaExitosa($resultado['data'], 201, $resultado['message'])
            : $this->respuestaError($resultado['message'], $resultado['status']);
    }
}
