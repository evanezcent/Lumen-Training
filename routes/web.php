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

$router->get($var.'/boards', 'BoardController@index');
$router->post($var.'/add-board', 'BoardController@store');
$router->get($var.'/board/{id}', 'BoardController@show');
$router->put($var.'/update-board/{boardID}', 'BoardController@update');
$router->delete($var.'/delete-board/{id}', 'BoardController@delete');

$router->post($var.'/login', 'UserController@login');
$router->post($var.'/logout', 'UserController@logout');
$router->post($var.'/register', 'UserController@register');

$router->get($var.'/board/{boardID}/list', 'ListController@getBoardsList');
$router->get($var.'/board/{boardID}/list/{listID}', 'ListController@getList');
$router->post($var.'/add-list/{boardID}', 'ListController@addList');
$router->put($var.'/board/{boardID}/update-list/{listID}', 'ListController@updateList');
$router->put($var.'/board/{boardID}/update-lists/{listID}', 'ListController@updateBoardList');
$router->delete($var.'/board/{boardID}/delete-list/{listID}', 'ListController@deleteList');

$router->get($var.'/board/{boardID}/list/{listID}/card', 'CardController@getListCards');
// $router->get($var.'/board/{boardID}/list/{listID}/card', 'CardController@getCard');
$router->post($var.'/add-list/{boardID}/card', 'CardController@addCard');
$router->put($var.'/board/{boardID}/list/{listID}/update-card/{cardID}', 'CardController@updateCard');
$router->put($var.'/board/{boardID}/list/{listID}/update-card/{cardID}', 'CardController@updateBoardCard');
$router->delete($var.'/board/{boardID}/list/{listID}/update-card/{cardID}', 'CardController@deleteCard');