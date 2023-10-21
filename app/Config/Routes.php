<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/', 'Home::index');
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

$routes->get('dashboard', 'DashboardController::index', ['filter' => 'auth']);

$routes->post('offices/list', 'OfficeController::getall', ['filter' => 'groupfilter:admin']);
$routes->post('tickets/list', 'TicketController::getall', ['filter' => 'auth']);

$routes->resource('offices', ['controller' => 'OfficeController', 'filter' => 'groupfilter:admin', 'except' => ['new,edit']]);
$routes->resource('tickets', ['controller' => 'TicketController', 'filter' => 'auth', 'except' => ['new,edit']]);

service('auth')->routes($routes);
