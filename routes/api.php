<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/auth/register', 'App\Http\Controllers\Api\Auth\RegisterController@store');
Route::post('/auth/token', 'App\Http\Controllers\Api\Auth\AuthClientController@auth');

Route::group(
    [
    'middleware' => ['auth:sanctum']
    ], function () {
        Route::get('/auth/me', 'App\Http\Controllers\Api\Auth\AuthClientController@me');
        Route::post('/auth/logout', 'App\Http\Controllers\Api\Auth\AuthClientController@logout');

        Route::post('/auth/v1/orders/{identifyOrder}/evaluations', 'App\Http\Controllers\Api\EvaluationApiController@store');

        Route::get('/auth/v1/my-orders', 'App\Http\Controllers\Api\OrderApiController@myOrders');
        Route::post('/auth/v1/orders', 'App\Http\Controllers\Api\OrderApiController@store');
    }
);

Route::group(
    [
    'prefix' => 'v1'
    ],
    function () {

        Route::get('/tenants/{uuid}', 'App\Http\Controllers\Api\TenantApiController@show');
        Route::get('/tenants', 'App\Http\Controllers\Api\TenantApiController@index');

        Route::get('/categories/{identify}', 'App\Http\Controllers\Api\CategoryApiController@show');
        Route::get('/categories', 'App\Http\Controllers\Api\CategoryApiController@categoriesByTenant');

        Route::get('/tables/{identify}', 'App\Http\Controllers\Api\TableApiController@show');
        Route::get('/tables', 'App\Http\Controllers\Api\TableApiController@tablesByTenant');

        Route::get('/products/{identify}', 'App\Http\Controllers\Api\ProductApiController@show');
        Route::get('/products', 'App\Http\Controllers\Api\ProductApiController@productsByTenant');

        

        Route::post('/orders', 'App\Http\Controllers\Api\OrderApiController@store');
        Route::get('/orders/{identify}', 'App\Http\Controllers\Api\OrderApiController@show');
    }
);

/**
 * Test API
 */
Route::get(
    '/', function () {
        return response()->json(['message' => 'ok']);
    }
);
