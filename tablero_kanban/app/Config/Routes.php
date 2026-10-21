<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');


$routes->group('api',['filter' =>'cors'], static function(RouteCollection $routes):void{


    $routes->options('(:any)', static function(){});

    $routes->get('tareas','TareasController::mostrar');
    $routes->get('tareaBuscar/(:num)','TareasController::buscar/$1');
    $routes->post('tareaCrear','TareasController::crear');
    $routes->put('tareaActualizar/(:num)','TareasController::actualizar/$1');
    $routes->delete('tareaEliminar/(:num)','TareasController::eliminar/$1');

    $routes->get('usuarios','UsuariosController::mostrar');
    $routes->get('usuarioBuscar/(:num)','UsuariosController::buscar/$1');
    $routes->post('usuarioCrear','UsuariosController::crear');
    $routes->put('usuarioActualizar/(:num)','UsuariosController::actualizar/$1');
    $routes->delete('usuarioEliminar/(:num)','UsuariosController::eliminar/$1');

    $routes->get('categorias','CategoriasController::mostrar');
    $routes->get('categoriaBuscar/(:num)','CategoriasController::buscar/$1');
    $routes->post('categoriaCrear','CategoriasController::crear');
    $routes->put('categoriaActualizar/(:num)','CategoriasController::actualizar/$1');
    $routes->delete('categoriaEliminar/(:num)','CategoriasController::eliminar/$1');

});