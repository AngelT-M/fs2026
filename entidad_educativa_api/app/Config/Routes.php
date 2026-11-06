<?php

use App\Controllers\AuthController as LegacyAuthController;
use App\Controllers\Api\AcademicoController;
use App\Controllers\Api\AsistenciaController;
use App\Controllers\Api\AuthController;
use App\Controllers\Api\CrudController;
use App\Controllers\Api\DashboardController;
use App\Controllers\Api\EvaluacionesController;
use App\Controllers\Api\ExamenesController;
use App\Controllers\Api\FinanzasController;
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->group('api', ['filter' => 'cors'], static function (RouteCollection $routes): void {
    $routes->options('(:any)', static function (): void {});
    $routes->post('auth/login', [AuthController::class, 'login']);

    $routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes): void {
        $routes->post('auth/logout', [AuthController::class, 'logout']);
        $routes->get('auth/me', [AuthController::class, 'me']);

        $routes->group('usuarios', ['filter' => 'role:1'], static function (RouteCollection $routes): void {
            $routes->get('/', [CrudController::class, 'index']);
            $routes->get('(:num)', [CrudController::class, 'show']);
            $routes->post('/', [CrudController::class, 'create']);
            $routes->put('(:num)', [CrudController::class, 'update']);
            $routes->delete('(:num)', [CrudController::class, 'delete']);
        });
        $routes->put('usuarios/(:num)/password', [CrudController::class, 'cambiarPassword']);

        foreach (['alumnos', 'maestros', 'personal'] as $perfil) {
            $filtroLectura = $perfil === 'alumnos' ? 'role:1,2,3,5' : 'role:1,2,3';
            $routes->get($perfil, [CrudController::class, 'index'], ['filter' => $filtroLectura]);
            $routes->get($perfil . '/(:num)', [CrudController::class, 'show'], ['filter' => $filtroLectura]);
            $routes->post($perfil, [CrudController::class, 'create'], ['filter' => 'role:1,2,3']);
            $routes->put($perfil . '/(:num)', [CrudController::class, 'update'], ['filter' => 'role:1,2,3']);
            $routes->delete($perfil . '/(:num)', [CrudController::class, 'delete'], ['filter' => 'role:1,2,3']);
        }

        foreach (['niveles', 'grados', 'secciones', 'cursos', 'grados-secciones'] as $catalogo) {
            $routes->get("academico/{$catalogo}", [AcademicoController::class, 'index']);
        }
        $routes->post('academico/cursos', [AcademicoController::class, 'crearCurso'], ['filter' => 'role:1']);
        $routes->put('academico/cursos/(:num)', [AcademicoController::class, 'actualizarCurso'], ['filter' => 'role:1']);
        $routes->post('academico/grados-secciones', [AcademicoController::class, 'abrirAula'], ['filter' => 'role:1,2,3']);
        $routes->post('academico/asignaciones/alumnos', [AcademicoController::class, 'asignarAlumno'], ['filter' => 'role:1,2,3']);
        $routes->post('academico/asignaciones/maestros', [AcademicoController::class, 'asignarMaestro'], ['filter' => 'role:1,2,3']);

        $routes->get('evaluaciones/cursos/(:num)/actividades', [EvaluacionesController::class, 'actividades']);
        $routes->post('evaluaciones/cursos/(:num)/actividades', [EvaluacionesController::class, 'crearActividad'], ['filter' => 'role:1,2,3,4']);
        $routes->post('evaluaciones/calificaciones', [EvaluacionesController::class, 'calificar'], ['filter' => 'role:1,2,3,4']);
        $routes->get('evaluaciones/actividades/(:num)/calificaciones', [EvaluacionesController::class, 'listarCalificacionesActividad'], ['filter' => 'role:1,2,3,4']);
        $routes->get('evaluaciones/alumnos/(:num)/boleta', [EvaluacionesController::class, 'boleta']);
        $routes->get('cursos/(:num)/actividades', [EvaluacionesController::class, 'actividades']);
        $routes->post('cursos/(:num)/actividades', [EvaluacionesController::class, 'crearActividad'], ['filter' => 'role:1,2,3,4']);
        $routes->post('calificaciones', [EvaluacionesController::class, 'calificar'], ['filter' => 'role:1,2,3,4']);
        $routes->get('alumnos/(:num)/boleta', [EvaluacionesController::class, 'boleta']);

        $routes->get('asistencia/grado-seccion/(:num)', [AsistenciaController::class, 'consultarPorAulaYFecha'], ['filter' => 'role:1,2,3,4']);
        $routes->get('alumnos/(:num)/asistencia', [AsistenciaController::class, 'consultarHistorialAlumno'], ['filter' => 'role:1,2,3,4,5']);
        $routes->post('asistencia', [AsistenciaController::class, 'crear'], ['filter' => 'role:1,2,3,4']);
        $routes->post('examenes', [ExamenesController::class, 'crear'], ['filter' => 'role:1,4']);
        $routes->put('examenes/(:num)/autorizar', [ExamenesController::class, 'autorizar'], ['filter' => 'role:1,2,3']);

        $routes->get('alumnos/(:num)/cargos', [FinanzasController::class, 'cargosAlumno'], ['filter' => 'role:1,2,3,5']);
        $routes->post('cargos-alumnos/generar', [FinanzasController::class, 'generarCargosAlumnos'], ['filter' => 'role:1,2,3']);
        $routes->post('pagos/realizar', [FinanzasController::class, 'realizarPago'], ['filter' => 'role:3']);
        $routes->get('pagos/comprobante/(:num)', [FinanzasController::class, 'obtenerComprobante'], ['filter' => 'role:1,2,3']);
        $routes->get('calendario', [FinanzasController::class, 'calendario']);
        $routes->post('calendario', [FinanzasController::class, 'crearEvento'], ['filter' => 'role:1,2,3']);
        $routes->get('dashboard/resumen', [DashboardController::class, 'obtenerResumen'], ['filter' => 'role:1']);
    });

    $routes->add('(:any)', static function () {
        return service('response')
            ->setStatusCode(404)
            ->setJSON([
                'status' => 404,
                'error' => true,
                'message' => 'Ruta API no encontrada.',
                'data' => null,
            ]);
    });
});

$routes->group('api', ['filter' => 'cors'], static function (RouteCollection $routes): void {
    $routes->post('autenticacion/login', [LegacyAuthController::class, 'login']);
    $routes->post('autenticacion/logout', [LegacyAuthController::class, 'logout'], ['filter' => 'auth']);
});
