<?php

namespace App\Controllers\Api;

use App\Models\ExamenEnviadosModel;
use CodeIgniter\HTTP\ResponseInterface;

class ExamenesController extends ApiController
{
    public function crear(): ResponseInterface
    {
        $datos = $this->datosEntrada();
        if (! is_array($datos)) {
            return $this->respuestaError('El cuerpo debe ser un objeto JSON válido.', 400);
        }

        if ($error = $this->validarEntrada($datos, [
            'asignacion_curso_maestro_id' => 'required|integer|is_not_unique[asignacion_curso_maestro.asignacion_curso_maestro_id]',
            'titulo' => 'required|max_length[150]',
            'mensaje' => 'permit_empty|max_length[5000]',
            'archivo_url' => 'required|valid_url|max_length[255]',
        ])) {
            return $error;
        }

        $model = model(ExamenEnviadosModel::class, false);
        $usuario = $this->usuarioActual();
        $asignacionId = (int) $datos['asignacion_curso_maestro_id'];
        if (! $model->maestroPuedeGestionar($asignacionId, $usuario)) {
            return $this->respuestaError('No tienes acceso a esta asignación de curso.', 403);
        }

        $examen = $model->enviarExamen([
            'asignacion_curso_maestro_id' => $asignacionId,
            'titulo' => trim($datos['titulo']),
            'mensaje' => $datos['mensaje'] ?? null,
            'archivo_url' => trim($datos['archivo_url']),
        ]);

        return $examen === false
            ? $this->respuestaError('No fue posible registrar el examen.', 500)
            : $this->respuestaExitosa($examen, 201, 'Examen enviado para autorización.');
    }

    public function autorizar(string $id): ResponseInterface
    {
        $id = $this->validarId($id);
        $datos = $this->datosEntrada();
        if ($id === null || $datos === null) {
            return $this->respuestaError('Identificador o cuerpo JSON inválido.', 400);
        }
        if ($error = $this->validarEntrada($datos, [
            'estado' => 'required|in_list[APROBADO,RECHAZADO]',
        ])) {
            return $error;
        }

        $resultado = model(ExamenEnviadosModel::class, false)->autorizarExamen(
            $id,
            $datos['estado'],
            (int) ($this->usuarioActual()['id_usuario'] ?? 0)
        );

        return $resultado['status'] === 200
            ? $this->respuestaExitosa($resultado['data'], 200, $resultado['message'])
            : $this->respuestaError($resultado['message'], $resultado['status']);
    }
}
