<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'middleware' => [
        'auth',
    ]
], function () {

    Route::get('/', 'DashboardController@showDashboard')
        ->name('dashboard');
});

Route::match(['get', 'post'],'/ajax', 'AjaxController@getGenres')->name('ajax');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

