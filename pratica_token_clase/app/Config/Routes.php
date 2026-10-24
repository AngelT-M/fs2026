<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

$routes->group('api',['filter'=>'cors'], static function( RouteCollection $routes):void{

    $routes->options('(:any)', static function(): void{});


});

