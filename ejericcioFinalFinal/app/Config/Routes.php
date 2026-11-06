<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->group('api',['filter'=>'cors'], static function (RouteCollection $routes):void{

    $routes->options('(:any)',static function(){});

    $routes->get('proyectos','ProyectosController::mostrar');
    $routes->get('proyectos/(:num)','ProyectosController::buscar/$1');
    $routes->post('proyectos','ProyectosController::crear');
    $routes->match(['PATCH','PUT'],'proyectos/(:num)','ProyectosController::actualizar/$1');
    $routes->delete('proyectos/(:num)','ProyectosController::eliminar/$1');

    $routes->get('categorias','CategoriasController::mostrar');

    $routes->get('proyectos/(:num)/tareas','ProTareasController::mostrar/$1');
    $routes->get('proyectos/(:num)/tareas/(:num)','ProTareasController::buscar/$1/$2');
    $routes->post('proyectos/(:num)/tareas','ProTareasController::crear/$1');
    $routes->match(['PATCH','PUT'],'proyectos/(:num)/tareas/(:num)','ProTareasController::actualizar/$1/$2');
    $routes->delete('proyectos/(:num)/tareas/(:num)','ProTareasController::eliminar/$1/$2');

});
