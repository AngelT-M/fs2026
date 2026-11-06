<?php

namespace App\Controllers\Api;

use App\Models\AsignacionCursoMaestroModel;
use App\Models\AsignacionGradosModel;
use App\Models\CursosModel;
use App\Models\GradosModel;
use App\Models\GradosSeccionesModel;
use App\Models\NivelesEducativosModel;
use App\Models\SeccionesModel;
use CodeIgniter\HTTP\ResponseInterface;

class AcademicoController extends ApiController
{
    public function index(): ResponseInterface
    {
        $catalogo = (string) $this->request->getUri()->getSegment(3);
        $data = match ($catalogo) {
            'niveles' => model(NivelesEducativosModel::class, false)->listarApi(),
            'grados' => model(GradosModel::class, false)->listarApi(),
            'secciones' => model(SeccionesModel::class, false)->listarApi(),
            'cursos' => model(CursosModel::class, false)->listarApi(),
            'grados-secciones' => model(GradosSeccionesModel::class, false)->listarApi(),
            default => null,
        };
        if ($data === null) {
            return $this->respuestaError('Catálogo no encontrado.', 404);
        }

        return $this->respuestaExitosa($data);
    }

    public function abrirAula(): ResponseInterface
    {
        $datos = $this->datosEntrada();
        if ($datos === null) {
            return $this->respuestaError('El cuerpo debe ser un objeto JSON válido.', 400);
        }
        if ($error = $this->validarEntrada($datos, [
            'grado_id' => 'required|integer|is_not_unique[grados.grado_id]',
            'seccion_id' => 'required|integer|is_not_unique[secciones.seccion_id]',
            'cantidad_estudiantes' => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[255]',
            'ciclo' => 'required|integer|greater_than_equal_to[1901]|less_than_equal_to[2155]',
        ])) {
            return $error;
        }

        $model = model(GradosSeccionesModel::class, false);
        $id = $model->guardarApi([
            'grado_id' => $datos['grado_id'],
            'seccion_id' => $datos['seccion_id'],
            'cantidad_estudiantes' => $datos['cantidad_estudiantes'],
            'ciclo' => $datos['ciclo'],
        ]);
        if ($id === false) {
            return $this->respuestaError('No fue posible abrir el aula.', 409);
        }

        return $this->respuestaExitosa($model->buscarAula($id), 201, 'Aula abierta.');
    }

    public function asignarAlumno(): ResponseInterface
    {
        $datos = $this->datosEntrada();
        if ($datos === null) {
            return $this->respuestaError('El cuerpo debe ser un objeto JSON válido.', 400);
        }
        if ($error = $this->validarEntrada($datos, [
            'alumno_id' => 'required|integer|is_not_unique[alumnos.alumno_id]',
            'grado_seccion_id' => 'required|integer|is_not_unique[grados_secciones.grado_seccion_id]',
        ])) {
            return $error;
        }

        $model = model(AsignacionGradosModel::class, false);
        if ($model->existeAsignacion((int) $datos['alumno_id'], (int) $datos['grado_seccion_id'])) {
            return $this->respuestaError('El alumno ya está asignado a esta aula.', 409);
        }

        $asignacion = $model->crearAsignacion($datos);
        return $asignacion === false
            ? $this->respuestaError('No fue posible asignar al alumno.', 409)
            : $this->respuestaExitosa($asignacion, 201, 'Alumno asignado.');
    }

    public function asignarMaestro(): ResponseInterface
    {
        $datos = $this->datosEntrada();
        if ($datos === null) {
            return $this->respuestaError('El cuerpo debe ser un objeto JSON válido.', 400);
        }
        if ($error = $this->validarEntrada($datos, [
            'maestro_id' => 'required|integer|is_not_unique[maestros.maestro_id]',
            'grado_seccion_id' => 'required|integer|is_not_unique[grados_secciones.grado_seccion_id]',
            'curso_id' => 'required|integer|is_not_unique[cursos.curso_id]',
        ])) {
            return $error;
        }

        $model = model(AsignacionCursoMaestroModel::class, false);
        if ($model->existeAsignacion(
            (int) $datos['maestro_id'],
            (int) $datos['grado_seccion_id'],
            (int) $datos['curso_id']
        )) {
            return $this->respuestaError('La asignación del maestro ya existe.', 409);
        }

        $asignacion = $model->crearAsignacion($datos);
        return $asignacion === false
            ? $this->respuestaError('No fue posible asignar el maestro.', 409)
            : $this->respuestaExitosa($asignacion, 201, 'Maestro asignado.');
    }

    public function crearCurso(): ResponseInterface
    {
        $datos = $this->datosEntrada();
        if ($datos === null) {
            return $this->respuestaError('El cuerpo debe ser un objeto JSON válido.', 400);
        }
        if (isset($datos['nom_curso']) && is_string($datos['nom_curso'])) {
            $datos['nom_curso'] = trim($datos['nom_curso']);
        }
        if ($error = $this->validarEntrada($datos, [
            'nom_curso' => 'required|max_length[100]|is_unique[cursos.nom_curso]',
            'descripcion' => 'permit_empty|max_length[1000]',
        ])) {
            return $error;
        }

        $curso = model(CursosModel::class, false)->crearApi([
            'nom_curso' => trim($datos['nom_curso']),
            'descripcion' => $datos['descripcion'] ?? null,
        ]);
        return $curso === false
            ? $this->respuestaError('No fue posible crear el curso.', 409)
            : $this->respuestaExitosa($curso, 201, 'Curso creado.');
    }

    public function actualizarCurso(string $id): ResponseInterface
    {
        $id = $this->validarId($id);
        $datos = $this->datosEntrada();
        if ($id === null || $datos === null) {
            return $this->respuestaError('Identificador o cuerpo JSON inválido.', 400);
        }
        $datos = array_intersect_key($datos, array_flip(['nom_curso', 'descripcion']));
        if ($datos === []) {
            return $this->respuestaError('No se enviaron campos editables.', 422);
        }
        if (isset($datos['nom_curso']) && is_string($datos['nom_curso'])) {
            $datos['nom_curso'] = trim($datos['nom_curso']);
        }

        $reglas = [];
        if (array_key_exists('nom_curso', $datos)) {
            $reglas['nom_curso'] = "required|max_length[100]|is_unique[cursos.nom_curso,curso_id,{$id}]";
        }
        if (array_key_exists('descripcion', $datos)) {
            $reglas['descripcion'] = 'permit_empty|max_length[1000]';
        }
        if ($error = $this->validarEntrada($datos, $reglas)) {
            return $error;
        }

        $curso = model(CursosModel::class, false)->actualizarApi($id, $datos);
        return $curso === null
            ? $this->respuestaError('No se encontró el curso solicitado.', 404)
            : ($curso === false
                ? $this->respuestaError('No fue posible actualizar el curso.', 409)
                : $this->respuestaExitosa($curso, 200, 'Curso actualizado.'));
    }
}
