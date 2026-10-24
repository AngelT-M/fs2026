<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');


$routes->group('api',['filter'=>'cors'], static function(RouteCollection $routes):void{
    $db = \config\DataBae::connect();
    $routes->options('(:any)', static function(){});

    $routes->get('tareas','TareasController::mostrar');
    $routes->get('tareas/(:num)', 'TareasController::buscar/$1');
    $routes->post('tareas', 'TareasController::crear');
    $routes->put('tareas/(:num)', 'TareasController::actualizar/$1');
    $routes->delete('tareas/(:num)', 'TareasController::eliminar/$1');

    $routes->get('usuarios','UsuariosController::mostrar');
    $routes->get('usuarios/(:num)', 'UsuariosController::buscar/$1');
    $routes->post('usuarios', 'UsuariosController::crear');
    $routes->put('usuarios/(:num)', 'UsuariosController::actualizar/$1');
    $routes->delete('usuarios/(:num)', 'UsuariosController::eliminar/$1');

    $routes->get('tokens', 'TokensController::mostrar');
    $routes->get('tokens/(:num)', 'TokensController::buscar/$1');
    $routes->post('tokens', 'TokensController::crear');
    $routes->put('tokens/(:num)', 'TokensController::actualizar/$1');
    $routes->delete('tokens/(:num)', 'TokensController::eliminar/$1');

});