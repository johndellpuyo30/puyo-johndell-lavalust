<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

/** @var object $router */

// Laboratory Exercise No. 5
$router->get('/', 'AuthController::login');
$router->get('/login', 'AuthController::login');
$router->post('/login', 'AuthController::authenticate');
$router->get('/logout', 'AuthController::logout');

$router->get('/products', 'ProductController::index')->middleware('auth');
$router->get('/products/create', 'ProductController::create')->middleware('auth');
$router->post('/products/store', 'ProductController::store')->middleware('auth');
$router->get('/products/edit/{id}', 'ProductController::edit')->where_number('id')->middleware('auth');
$router->post('/products/update/{id}', 'ProductController::update')->where_number('id')->middleware('auth');
$router->get('/products/delete/{id}', 'ProductController::confirmDelete')->where_number('id')->middleware('auth');
$router->post('/products/delete/{id}', 'ProductController::delete')->where_number('id')->middleware('auth');

// Existing project routes kept intact
$router->get('/student', 'StudentController::index');
$router->get('/student/profile', 'StudentController::profile')->middleware('student');

$router->get('/users', 'UsersController::index');
$router->get('/users/create', 'UsersController::create');
$router->post('/users/store', 'UsersController::store');
$router->get('/users/edit/{id}', 'UsersController::edit')->where_number('id');
$router->post('/users/update/{id}', 'UsersController::update')->where_number('id');
$router->post('/users/delete/{id}', 'UsersController::delete')->where_number('id');
