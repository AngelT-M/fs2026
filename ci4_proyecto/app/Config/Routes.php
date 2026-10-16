<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->get('categoria','Categorias::cat');

$routes->get('tarea','Tareas::tar');

$routes->get('proyecto','Proyecto::pro');

$routes->get('usuario','Usuario::usua');

$routes->post('guardar_categoria','Categorias::guardar');

$routes->post('guardar_tarea','Tareas::guardar');