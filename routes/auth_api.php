<?php

use Illuminate\Support\Facades\Route;

Route::group(
    [
    'prefix' => 'v1'
    ], function () {
        Route::get('/my-orders', 'App\Http\Controllers\Api\Auth\OrderTenantController@index')->middleware(['auth']);
        Route::patch('/my-orders', 'App\Http\Controllers\Api\Auth\OrderTenantController@update')->middleware(['auth']);
    }
);
