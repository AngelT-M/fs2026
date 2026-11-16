<?php

namespace App\Controllers\Api;

use App\Models\AsignacionCursoMaestroModel;
use App\Models\AsignacionGradosModel;
use App\Models\BimestresModel;
use App\Models\CursosModel;
use App\Models\GradosModel;
use App\Models\GradosSeccionesModel;
use App\Models\NivelesEducativosModel;
use App\Models\SeccionesModel;
use CodeIgniter\HTTP\ResponseInterface;

class AcademicoController extends ApiController
{
    public function bimestres(): ResponseInterface
    {
        $ciclo = $this->request->getGet('ciclo');
        if ($ciclo !== null) {
            $ciclo = filter_var($ciclo, FILTER_VALIDATE_INT);
            if ($ciclo === false || $ciclo < 1901 || $ciclo > 2155) {
                return $this->respuestaError('El ciclo escolar solicitado no es válido.', 400);
            }
        }

        return $this->respuestaExitosa(
            model(BimestresModel::class, false)->listarApi($ciclo === null ? null : (int) $ciclo)
        );
    }

    public function crearBimestresCiclo(): ResponseInterface
    {
        $datos = $this->datosEntrada();
        if ($datos === null) {
            return $this->respuestaError('El cuerpo debe ser un objeto JSON válido.', 400);
        }
        if ($error = $this->validarEntrada($datos, [
            'ciclo' => 'required|integer|greater_than_equal_to[1901]|less_than_equal_to[2155]',
        ])) {
            return $error;
        }

        $ciclo = (int) $datos['ciclo'];
        $bimestres = model(BimestresModel::class, false)->crearCiclo($ciclo);

        return $bimestres === false
            ? $this->respuestaError('Ya existen periodos registrados para ese ciclo o no fue posible crearlos.', 409)
            : $this->respuestaExitosa($bimestres, 201, 'Periodos creados para el ciclo escolar.');
    }

    public function actualizarFechasBimestre(string $id): ResponseInterface
    {
        $id = $this->validarId($id);
        $datos = $this->datosEntrada();
        if ($id === null || $datos === null) {
            return $this->respuestaError('Identificador o cuerpo JSON inválido.', 400);
        }
        if (! array_key_exists('fecha_inicio', $datos) || ! array_key_exists('fecha_fin', $datos)) {
            return $this->respuestaError('Debes enviar fecha_inicio y fecha_fin; ambas pueden quedar vacías.', 422);
        }

        $fechaInicio = $this->normalizarFecha($datos['fecha_inicio']);
        $fechaFin = $this->normalizarFecha($datos['fecha_fin']);
        if (
            $fechaInicio === false
            || $fechaFin === false
            || (($fechaInicio === null) !== ($fechaFin === null))
        ) {
            return $this->respuestaError('Las fechas deben ser válidas y configurarse ambas o dejarse ambas vacías.', 422);
        }
        if ($fechaInicio !== null && $fechaFin !== null && $fechaInicio > $fechaFin) {
            return $this->respuestaError('La fecha de inicio no puede ser posterior a la fecha final.', 422);
        }
        if (
            $fechaInicio !== null
            && $fechaFin !== null
            && model(BimestresModel::class, false)->fechasSeSuperponen($id, $fechaInicio, $fechaFin)
        ) {
            return $this->respuestaError('Las fechas se superponen con otro periodo del mismo ciclo.', 409);
        }

        $resultado = model(BimestresModel::class, false)
            ->actualizarFechas($id, $fechaInicio, $fechaFin);
        if ($resultado === null) {
            return $this->respuestaError('No se encontró el periodo solicitado.', 404);
        }

        return $resultado === false
            ? $this->respuestaError('No fue posible actualizar las fechas del periodo.', 409)
            : $this->respuestaExitosa($resultado, 200, 'Fechas del periodo actualizadas.');
    }

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
        if ($model->existeAulaEnCiclo(
            (int) $datos['grado_id'],
            (int) $datos['seccion_id'],
            (int) $datos['ciclo']
        )) {
            return $this->respuestaError('Ya existe esta sección para el grado en el ciclo escolar seleccionado.', 409);
        }

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

    public function eliminarAula(string $id): ResponseInterface
    {
        $id = $this->validarId($id);
        if ($id === null) {
            return $this->respuestaError('Identificador inválido.', 400);
        }

        $model = model(GradosSeccionesModel::class, false);
        $aula = $model->buscarAula($id);
        if ($aula === null) {
            return $this->respuestaError('No se encontró el aula solicitada.', 404);
        }
        if ($model->tieneAsignaciones($id)) {
            return $this->respuestaError(
                'No se puede eliminar el aula porque tiene estudiantes o docentes asignados.',
                409
            );
        }
        if (! $model->eliminarApi($id)) {
            return $this->respuestaError('No fue posible eliminar el aula.', 409);
        }

        return $this->respuestaExitosa(null, 200, 'Aula eliminada.');
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

    public function listarAsignacionesMaestros(): ResponseInterface
    {
        $usuario = $this->usuarioActual();
        $asignaciones = model(AsignacionCursoMaestroModel::class, false)
            ->listarAsignacionesParaUsuario($usuario);

        return $this->respuestaExitosa($asignaciones);
    }

    private function normalizarFecha(mixed $fecha): string|null|false
    {
        if ($fecha === null || $fecha === '') {
            return null;
        }
        if (! is_string($fecha)) {
            return false;
        }

        $fechaNormalizada = \DateTimeImmutable::createFromFormat('!Y-m-d', $fecha);
        $errores = \DateTimeImmutable::getLastErrors();

        return $fechaNormalizada !== false
            && ($errores === false || ($errores['warning_count'] === 0 && $errores['error_count'] === 0))
            && $fechaNormalizada->format('Y-m-d') === $fecha
                ? $fecha
                : false;
    }
}
