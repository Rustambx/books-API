<?php
Route::group([
    'middleware' => ['web', 'auth'],
    'prefix' => 'user',
    'namespace' => 'App\Modules\User\Controllers',

], function () {
    Route::get('/list', 'UserController@showList')->name('user');
    Route::get('/add', 'UserController@addForm')->name('user.add');
    Route::post('save', 'UserController@save')->name('user.save');

    Route::get('/edit/{id}', 'UserController@editUser')
        ->name('user.edit');

    Route::delete('/delete/{id}', 'UserController@deleteUser')
        ->name('user.delete');

    Route::get('/permissions', 'UserController@showPermissions')->name('user.permission');
    Route::post('/permissions/save', 'UserController@savePermissions')->name('user.permission.save');

});
