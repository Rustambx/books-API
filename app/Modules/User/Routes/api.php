<?php
Route::group([
    'middleware' => 'api',
    'prefix' => 'api',
    'namespace' => 'App\Modules\User\Controllers',
], function () {

    Route::get('user/library/{id}', 'UserController@getUserLibrary');
    Route::get('user/profile', 'UserController@getProfile');

    Route::post('signin', 'UserController@signIn');
    Route::post('signup', 'UserController@signUp');
    Route::post('user/edit/{id}', 'UserController@editProfile');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'ResetPasswordController@reset');
});
