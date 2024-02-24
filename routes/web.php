<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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

$router->group(['prefix' => 'event'], function () use ($router) {
    $router->get('', ['uses' => 'EventController@index']); // Mostar todos los registros
    $router->get('/{id}', ['uses' => 'EventController@show']); // Muestra solo un registro
    $router->post('', ['uses' => 'EventController@store']); // Crea un registro
    $router->put('/{id}', ['uses' => 'EventController@update']); // Actualiza un registro
    $router->delete('/{id}', ['uses' => 'EventController@destroy']); // Elimina un registro
    $router->get('/{id}/guests', ['uses' => 'EventGuestController@showByEvent']);
});

$router->group(['prefix' => 'dresscode'], function () use ($router) {
    $router->get('', ['uses' => 'EventDressCodeController@index']); // Mostar todos los registros
    $router->get('/{id}', ['uses' => 'EventDressCodeController@show']); // Muestra solo un registro
    $router->post('', ['uses' => 'EventDressCodeController@store']); // Crea un registro
    $router->put('/{id}', ['uses' => 'EventDressCodeController@update']); // Actualiza un registro
    $router->delete('/{id}', ['uses' => 'EventDressCodeController@destroy']); // Elimina un registro
});

$router->group(['prefix' => 'guest'], function () use ($router) {
    $router->get('', ['uses' => 'EventGuestController@index']); // Mostar todos los registros
    $router->get('/{id}', ['uses' => 'EventGuestController@show']); // Muestra solo un registro
    $router->post('', ['uses' => 'EventGuestController@store']); // Crea un registro
    $router->put('/{id}', ['uses' => 'EventGuestController@update']); // Actualiza un registro
    $router->delete('/{id}', ['uses' => 'EventGuestController@destroy']); // Elimina un registro
    // $router->get('/event/{id}', ['uses' => 'EventGuestController@showByEvent']); // Muestra solo un registro
});

$router->group(['prefix' => 'images'], function () use ($router) {
    $router->get('', ['uses' => 'EventImagesController@index']); // Mostar todos los registros
    $router->get('/{id}', ['uses' => 'EventImagesController@show']); // Muestra solo un registro
    $router->post('', ['uses' => 'EventImagesController@store']); // Crea un registro
    $router->put('/{id}', ['uses' => 'EventImagesController@update']); // Actualiza un registro
    $router->delete('/{id}', ['uses' => 'EventImagesController@destroy']); // Elimina un registro
});

$router->group(['prefix' => 'locations'], function () use ($router) {
    $router->get('', ['uses' => 'EventLocationsController@index']); // Mostar todos los registros
    $router->get('/{id}', ['uses' => 'EventLocationsController@show']); // Muestra solo un registro
    $router->post('', ['uses' => 'EventLocationsController@store']); // Crea un registro
    $router->put('/{id}', ['uses' => 'EventLocationsController@update']); // Actualiza un registro
    $router->delete('/{id}', ['uses' => 'EventLocationsController@destroy']); // Elimina un registro
});

$router->post('/upload', 'UploadController@uploadFile');
$router->get('/invite/{id}/{guest}', 'EventController@invite');