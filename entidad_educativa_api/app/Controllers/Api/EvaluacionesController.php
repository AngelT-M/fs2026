<?php

namespace App\Controllers\Api;

use App\Models\ActividadesCursoModel;
use App\Models\AlumnosModel;
use App\Models\AsignacionCursoMaestroModel;
use App\Models\CalificacionesModel;
use CodeIgniter\HTTP\ResponseInterface;

class EvaluacionesController extends ApiController
{
    public function actividades(string $idAcm): ResponseInterface
    {
        $idAcm = $this->validarId($idAcm);
        if ($idAcm === null) {
            return $this->respuestaError('Identificador de asignación inválido.', 400);
        }
        if (! model(AsignacionCursoMaestroModel::class, false)->puedeConsultar($idAcm, $this->usuarioActual())) {
            return $this->respuestaError('No se encontró la asignación solicitada.', 404);
        }

        return $this->respuestaExitosa(
            model(ActividadesCursoModel::class, false)->listarPorAsignacion($idAcm)
        );
    }

    public function crearActividad(string $idAcm): ResponseInterface
    {
        $idAcm = $this->validarId($idAcm);
        $datos = $this->datosEntrada();
        if ($idAcm === null || $datos === null) {
            return $this->respuestaError('Identificador o cuerpo JSON inválido.', 400);
        }

        $datos['asignacion_curso_maestro_id'] = $idAcm;
        if ($error = $this->validarEntrada($datos, [
            'asignacion_curso_maestro_id' => 'required|integer|is_not_unique[asignacion_curso_maestro.asignacion_curso_maestro_id]',
            'bimestre' => 'required|in_list[1er Bimestre,2do Bimestre,3er Bimestre,4to Bimestre,Recuperacion]',
            'nom_actividad' => 'required|max_length[100]',
            'punteo_maximo' => 'required|decimal|greater_than[0]|less_than_equal_to[999.99]',
            'descripcion' => 'permit_empty|max_length[1000]',
        ])) {
            return $error;
        }

        $usuario = $this->usuarioActual();
        if (
            (int) ($usuario['rol_id'] ?? 0) === 4
            && ! model(AsignacionCursoMaestroModel::class, false)
                ->maestroPuedeGestionar($idAcm, (int) ($usuario['id_usuario'] ?? 0))
        ) {
            return $this->respuestaError('No tienes acceso a esta asignación de curso.', 403);
        }

        $actividad = model(ActividadesCursoModel::class, false)->crearActividad([
            'asignacion_curso_maestro_id' => $idAcm,
            'bimestre' => $datos['bimestre'],
            'nom_actividad' => $datos['nom_actividad'],
            'punteo_maximo' => $datos['punteo_maximo'],
            'descripcion' => $datos['descripcion'] ?? null,
        ]);

        return $actividad === false
            ? $this->respuestaError('No fue posible crear la actividad.', 409)
            : $this->respuestaExitosa($actividad, 201);
    }

    public function calificar(): ResponseInterface
    {
        $datos = $this->datosEntrada();
        if ($datos === null) {
            return $this->respuestaError('El cuerpo debe ser un objeto JSON válido.', 400);
        }

        $items = $datos['calificaciones'] ?? [$datos];
        if (! is_array($items) || $items === []) {
            return $this->respuestaError('Debe enviar al menos una calificación.', 422);
        }
        foreach ($items as $item) {
            if (! is_array($item) || ($error = $this->validarEntrada($item, [
                'asignacion_grado_id' => 'required|integer|is_not_unique[asignacion_grados.asignacion_grado_id]',
                'actividad_curso_id' => 'required|integer|is_not_unique[actividades_curso.actividad_curso_id]',
                'punteo_obtenido' => 'required|decimal|greater_than_equal_to[0]',
            ]))) {
                return $error ?? $this->respuestaError('Una o más calificaciones tienen datos inválidos.', 422);
            }
        }

        $guardadas = model(CalificacionesModel::class, false)
            ->guardarCalificaciones($items, $this->usuarioActual());
        return $guardadas === false
            ? $this->respuestaError('La calificación no corresponde a esa actividad, excede el punteo máximo o no tienes acceso.', 422)
            : $this->respuestaExitosa($guardadas, 201, 'Calificaciones registradas.');
    }

    public function listarCalificacionesActividad(string $actividadId): ResponseInterface
    {
        $actividadId = $this->validarId($actividadId);
        if ($actividadId === null) {
            return $this->respuestaError('Identificador de actividad inválido.', 400);
        }

        $calificaciones = model(CalificacionesModel::class, false)
            ->listarPorActividad($actividadId, $this->usuarioActual());
        return $calificaciones === false
            ? $this->respuestaError('No se encontró la actividad o no tienes acceso.', 404)
            : $this->respuestaExitosa($calificaciones);
    }

    public function boleta(string $alumnoId): ResponseInterface
    {
        $alumnoId = $this->validarId($alumnoId);
        if ($alumnoId === null) {
            return $this->respuestaError('Identificador de alumno inválido.', 400);
        }

        $usuario = $this->usuarioActual();
        if (
            (int) ($usuario['rol_id'] ?? 0) === 5
            && ! model(AlumnosModel::class, false)->perteneceAUsuario($alumnoId, (int) $usuario['id_usuario'])
        ) {
            return $this->respuestaError('No se encontró el alumno solicitado.', 404);
        }
        if (
            (int) ($usuario['rol_id'] ?? 0) === 4
            && ! model(AsignacionCursoMaestroModel::class, false)
                ->maestroTieneAlumno($alumnoId, (int) $usuario['id_usuario'])
        ) {
            return $this->respuestaError('No se encontró el alumno solicitado.', 404);
        }

        return $this->respuestaExitosa(
            model(CalificacionesModel::class, false)->obtenerBoleta($alumnoId)
        );
    }
}
