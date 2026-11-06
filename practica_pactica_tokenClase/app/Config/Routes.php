<?php


use App\Controllers\AuthoController;
use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');


$routes->group('api',['filter' =>'cors'], static function(RouteCollection $routes):void{

    $routes->options('(:any)', static function(): void{});

    $routes->post('autenticacion/login', [AuthoController::class, 'login']);

    $routes->group('', ['filter'=>'auth'], static function(RouteCollection $routes):void{
        $routes->post('autenticacion/logout', [AuthoController::class, 'logout']);
    });



});


