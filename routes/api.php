<?php

use Illuminate\Http\Request;

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

Route::group(['prefix' => 'auth'], function ($router) {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('me', 'AuthController@me');
});

Route::group(['middleware' => 'jwt.auth'], function ($router) {
    Route::post('customers/new', 'CustomersController@new');
    Route::get('customers', 'CustomersController@all');
    Route::get('customers/export', 'CustomersController@export');
    Route::get('customers/{id}', 'CustomersController@get');
    Route::post('customers/update/{id}', 'CustomersController@update');
    Route::get('search/customers/{field}/{query}', 'CustomersController@search');
    Route::delete('customers/delete/{id}', 'CustomersController@destroy');
    Route::get('customers/log/show', 'CustomersController@log');
    Route::get('customers/log/export', 'CustomersController@activityExport');
    Route::get('customers/log/export/csv', 'CustomersController@activityExportToCsv');
});

