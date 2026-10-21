<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');


$routes->group('api', ['filter'=>'cors'], static function(RouteCollection $routes):void{


    $routes->options('(:any)', static function(){});

    $routes->get('tareas','TareasController::mostrar');
    $routes->get('tareaBuscar/(:num)','TareasController::buscar/$1');
    $routes->post('tareaCrear','TareasController::crear');
    $routes->put('tareaActualizar/(:num)','TareasController::actualizar/$1');
    $routes->delete('tareaEliminar/(:num)', 'TareasController::eliminar/$1');

    $routes->get('usuarios','UsuariosController::mostrar');
    $routes->get('usuariosBuscar/(:num)','UsuariosController::buscar/$1');
    $routes->post('usuariosCrear','UsuariosController::crear');
    $routes->put('usuariosActualizar/(:num)','UsuariosController::actualizar/$1');
    $routes->delete('usuariosEliminar/(:num)', 'UsuariosController::eliminar/$1');

     $routes->get('categorias','CategoriasController::mostrar');
    $routes->get('categoriasBuscar/(:num)','CategoriasController::buscar/$1');
    $routes->post('categoriasCrear','CategoriasController::crear');
    $routes->put('categoriasActualizar/(:num)','CategoriasController::actualizar/$1');
    $routes->delete('categoriasEliminar/(:num)', 'CategoriasController::eliminar/$1');




});