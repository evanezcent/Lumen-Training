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
$router->get('/', ['middleware'=>'auth', function () use ($router) {
    return $router->app->version();
}]);

$router->get($var.'/get-all-board', 'BoardController@index');
$router->post($var.'/add-board', 'BoardController@store');
$router->get($var.'/get-board/{id}', 'BoardController@show');
$router->put($var.'/update-board/{boardID}', 'BoardController@update');
$router->delete($var.'/delete-board/{id}', 'BoardController@delete');

$router->post($var.'/login', 'UserController@login');
$router->post($var.'/logout', 'UserController@logout');
$router->post($var.'/register', 'UserController@register');
