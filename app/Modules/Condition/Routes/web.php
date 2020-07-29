<?php
Route::group([
    'middleware' => ['web', 'auth'],
    'prefix' => 'condition',
    'namespace' => 'App\Modules\Condition\Controllers',

], function () {
    Route::get('/list', 'ConditionController@showList')->name('condition');
    Route::get('/show/{id}', 'ConditionController@showDetail')->name('condition.show');
    Route::get('/add', 'ConditionController@addForm')->name('condition.add');
    Route::post('save', 'ConditionController@save')->name('condition.save');

    Route::get('/edit/{id}', 'ConditionController@editCondition')
        ->name('condition.edit');

    Route::delete('/delete/{id}', 'ConditionController@deleteCondition')
        ->name('condition.delete');

});
