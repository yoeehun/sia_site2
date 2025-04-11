<?php
/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// Routes without '/api' prefix
$router->get('/users', 'UserController@getUsers'); 
$router->post('/users', 'UserController@add'); 
$router->get('/users/{identifier}', 'UserController@show'); 
$router->put('/users/{identifier}', 'UserController@update'); 
$router->patch('/users/{identifier}', 'UserController@patchUpdate'); 
$router->delete('/users/{identifier}', 'UserController@delete');

// Optional: Keep /api version too
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/users', 'UserController@getUsers');
    $router->post('/users', 'UserController@add');
    $router->get('/users/{identifier}', 'UserController@show');
    $router->put('/users/{identifier}', 'UserController@update');
    $router->patch('/users/{identifier}', 'UserController@patchUpdate');
    $router->delete('/users/{identifier}', 'UserController@delete');
});

// Catch-all route
$router->get('/{any:.*}', function () {
    return response()->json(['error' => 'Invalid endpoint. Please check your URL.'], 404);
});
