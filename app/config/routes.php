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

// Lab 6 React API
$router->get('/api/health', 'Lab6ApiController::health');
$router->post('/api/login', 'Lab6ApiController::login');
$router->post('/api/refresh', 'Lab6ApiController::refresh');
$router->post('/api/logout', 'Lab6ApiController::logout');
$router->get('/api/me', 'Lab6ApiController::me');
$router->get('/api/products', 'Lab6ApiController::products');
$router->post('/api/products', 'Lab6ApiController::create');
$router->get('/api/products/{id}', 'Lab6ApiController::show')->where_number('id');
$router->put('/api/products/{id}', 'Lab6ApiController::update')->where_number('id');
$router->patch('/api/products/{id}', 'Lab6ApiController::update')->where_number('id');
$router->delete('/api/products/{id}', 'Lab6ApiController::delete')->where_number('id');
foreach (['health','login','refresh','logout','me','products','products/{id}'] as $path) { $router->options('/api/'.$path, 'Lab6ApiController::preflight'); }
// CLI migration routes are rejected for HTTP requests by MigrationController.
$router->get('/migrate', 'MigrationController::migrate');
$router->get('/status', 'MigrationController::status');
$router->get('/create-migration/{name}', 'MigrationController::create_migration');
$router->get('/rollback', 'MigrationController::rollback');
$router->get('/rollback-all', 'MigrationController::rollback_all');
$router->get('/refresh', 'MigrationController::refresh');
$router->get('/lab6-seed', 'MigrationController::seed');
$router->get('/lab6-password/{username}', 'MigrationController::set_password');
