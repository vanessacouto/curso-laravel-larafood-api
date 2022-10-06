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

Route::get('/tenants/{uuid}', 'App\Http\Controllers\Api\TenantApiController@show');
Route::get('/tenants', 'App\Http\Controllers\Api\TenantApiController@index');

Route::get('/categories/{url}', 'App\Http\Controllers\Api\CategoryApiController@show');
Route::get('/categories', 'App\Http\Controllers\Api\CategoryApiController@categoriesByTenant');
