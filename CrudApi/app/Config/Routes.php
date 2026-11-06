<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');


$routes->group('api', ['filter'=> 'cors'], static function( RouteCollection $routes):void{

    $routes->options('(:any)', static function():void{});

    $routes->get('categorias','CategoriaController::mostrar');
    
    $routes->get('proyectos','ProyectoController::mostrar');
    $routes->get('proyectos/(:num)','ProyectoController::buscar/$1');
    $routes->post('proyectos','ProyectoController::crear');
    $routes->match(['patch','PUT'],'proyectos/(:num)','ProyectoController::actualizar/$1');
    $routes->delete('proyectos/(:num)','ProyectoController::eliminar/$1');

    $routes->get('proyectos/(:num)/tareas', 'TareaController::mostrar/$1');
    $routes->get('proyectos/(:num)/tareas/(:num)', 'TareaController::buscar/$1/$2');
    $routes->post('proyectos/(:num)/tareas', 'TareaController::crear/$1');
    $routes->match(['put', 'patch'], 'proyectos/(:num)/tareas/(:num)', 'TareaController::actualizar/$1/$2');
    $routes->delete('proyectos/(:num)/tareas/(:num)', 'TareaController::eliminar/$1/$2');




    
} );