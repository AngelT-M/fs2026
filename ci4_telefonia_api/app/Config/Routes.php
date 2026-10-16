<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/api/planes','Planes::index');
$routes->get('/api/planes/(:num)','Planes::show/$1');
$routes->post('/api/planes','Planes::create');
$routes->put('/api/planes/(:num)','Planes::update/$1');
$routes->delete('/api/planes/(:num)','Planes::delete/$1');
