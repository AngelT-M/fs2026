<?php

namespace App\Controllers\Api;

use App\Models\AsistenciaModel;
use CodeIgniter\HTTP\ResponseInterface;

class AsistenciaController extends ApiController
{
    public function consultarPorAulaYFecha(string $gradoSeccionId): ResponseInterface
    {
        $gradoSeccionId = $this->validarId($gradoSeccionId);
        $fecha = $this->request->getGet('fecha');
        if ($gradoSeccionId === null || ! is_string($fecha)) {
            return $this->respuestaError('Identificador o fecha inválidos.', 400);
        }
        if ($error = $this->validarEntrada(['fecha' => $fecha], [
            'fecha' => 'required|valid_date[Y-m-d]',
        ])) {
            return $error;
        }
        $asistencias = model(AsistenciaModel::class, false)
            ->listarPorAulaYFecha($gradoSeccionId, $fecha, $this->usuarioActual());
        if ($asistencias === null) {
            return $this->respuestaError('No se encontró el aula solicitada.', 404);
        }
        return $asistencias === false
            ? $this->respuestaError('No tienes acceso a esta aula.', 403)
            : $this->respuestaExitosa($asistencias);
    }

    public function consultarHistorialAlumno(string $alumnoId): ResponseInterface
    {
        $alumnoId = $this->validarId($alumnoId);
        if ($alumnoId === null) {
            return $this->respuestaError('Identificador de alumno inválido.', 400);
        }

        $historial = model(AsistenciaModel::class, false)
            ->obtenerHistorialAlumno($alumnoId, $this->usuarioActual());
        return $historial === false
            ? $this->respuestaError('No se encontró el alumno o no tienes acceso a su historial.', 404)
            : $this->respuestaExitosa($historial);
    }

    public function crear(): ResponseInterface
    {
        $datos = $this->datosEntrada();
        if ($datos === null) {
            return $this->respuestaError('El cuerpo debe ser un objeto JSON válido.', 400);
        }
        if ($error = $this->validarEntrada($datos, [
            'alumno_id' => 'required|integer|is_not_unique[alumnos.alumno_id]',
            'asignacion_grado_id' => 'required|integer|is_not_unique[asignacion_grados.asignacion_grado_id]',
            'fecha' => 'required|valid_date[Y-m-d]',
            'estado' => 'required|in_list[PRESENTE,AUSENTE,TARDE]',
        ])) {
            return $error;
        }

        $resultado = model(AsistenciaModel::class, false)
            ->registrarAsistenciaDiaria($datos, $this->usuarioActual());
        if ($resultado['status'] !== 201) {
            return $this->respuestaError($resultado['message'], $resultado['status']);
        }

        return $this->respuestaExitosa($resultado['data'], 201, $resultado['message']);
    }
}
