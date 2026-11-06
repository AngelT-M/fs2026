<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->group('api', static function ($routes): void {
    $routes->options('(:any)', static fn () => service('response')->setStatusCode(204));
    $routes->post('login', 'Api\AuthController::login');
});

$routes->group('api', ['filter' => 'auth'], static function ($routes): void {
    $routes->get('proyectos', 'Api\ProyectosController::index');
    $routes->get('proyectos/(:num)', 'Api\ProyectosController::show/$1');
    $routes->get('proyectos/(:num)/tareas', 'Api\TareasController::porProyecto/$1');

    $routes->get('tareas', 'Api\TareasController::index');
    $routes->get('usuarios', 'Api\UsuariosController::index');
    $routes->get('categorias', 'Api\CategoriasController::index');

    $routes->post('tareas', 
    'Api\TareasController::create');
    $routes->patch('tareas/(:num)/estado', 'Api\TareasController::cambiarEstado/$1');
    $routes->put('tareas/(:num)', 'Api\TareasController::update/$1');
    $routes->delete('tareas/(:num)', 'Api\TareasController::delete/$1');
});
