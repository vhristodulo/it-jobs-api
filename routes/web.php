<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});
 
$router->get('/jobs', 'JobController@list');
$router->get('/jobs/{id}', 'JobController@details');
$router->post('/jobs', 'JobController@save');
$router->put('/jobs', ['middleware' => 'jwt.auth', 'uses' => 'JobController@update']);
$router->delete('/jobs/{id}', ['middleware' => 'jwt.auth', 'uses' => 'JobController@delete']);

$router->get('/categories', 'JobCategoryController@list');
$router->get('/categories/{id}', ['middleware' => 'jwt.auth', 'uses' => 'JobCategoryController@details']);
$router->post('/categories', ['middleware' => 'jwt.auth', 'uses' => 'JobCategoryController@save']);
$router->put('/categories', ['middleware' => 'jwt.auth', 'uses' => 'JobCategoryController@update']);
$router->delete('/categories/{id}', ['middleware' => 'jwt.auth', 'uses' => 'JobCategoryController@delete']);

$router->get('/types', 'JobTypeController@list');
$router->get('/types/{id}', ['middleware' => 'jwt.auth', 'uses' => 'JobTypeController@details']);
$router->post('/types', ['middleware' => 'jwt.auth', 'uses' => 'JobTypeController@save']);
$router->put('/types', ['middleware' => 'jwt.auth', 'uses' => 'JobTypeController@update']);
$router->delete('/types/{id}', ['middleware' => 'jwt.auth', 'uses' => 'JobTypeController@delete']);

$router->get('/locations', 'JobLocationController@list');
$router->get('/locations/{id}', ['middleware' => 'jwt.auth', 'uses' => 'JobLocationController@details']);
$router->post('/locations', ['middleware' => 'jwt.auth', 'uses' => 'JobLocationController@save']);
$router->put('/locations', ['middleware' => 'jwt.auth', 'uses' => 'JobLocationController@update']);
$router->delete('/locations/{id}', ['middleware' => 'jwt.auth', 'uses' => 'JobLocationController@delete']);

$router->get('/emails', ['middleware' => 'jwt.auth', 'uses' => 'ListEmailController@list']);
$router->post('/subscribe', 'ListEmailController@subscribe');
$router->delete('/emails/{id}', ['middleware' => 'jwt.auth', 'uses' => 'ListEmailController@delete']);

$router->get('/users', ['middleware' => 'jwt.auth', 'uses' => 'UserController@list']);
$router->get('/users/{id}', ['middleware' => 'jwt.auth', 'uses' => 'UserController@details']);
$router->post('/users', ['middleware' => 'jwt.auth', 'uses' => 'UserController@save']);
$router->put('/users', ['middleware' => 'jwt.auth', 'uses' => 'UserController@update']);
$router->delete('/users/{id}', ['middleware' => 'jwt.auth', 'uses' => 'UserController@delete']);

$router->get('/roles', ['middleware' => 'jwt.auth', 'uses' => 'RoleController@list']);
$router->get('/roles/{id}', ['middleware' => 'jwt.auth', 'uses' => 'RoleController@details']);
$router->post('/roles', ['middleware' => 'jwt.auth', 'uses' => 'RoleController@save']);
$router->put('/roles', ['middleware' => 'jwt.auth', 'uses' => 'RoleController@update']);
$router->delete('/roles/{id}', ['middleware' => 'jwt.auth', 'uses' => 'RoleController@delete']);

$router->post('/auth/login', 'AuthController@authenticate');
