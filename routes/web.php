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
$var = "/learn";
$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get($var.'/get-all-board', ['middleware'=> 'auth', 'BoardController@index']);
$router->get($var.'/get-board/{id}', 'BoardController@show');
$router->post($var.'/add-board', 'BoardController@store');

$router->post($var.'/login', 'UserController@login');
$router->post($var.'/logout', 'UserController@logout');
$router->post($var.'/register', 'UserController@register');
