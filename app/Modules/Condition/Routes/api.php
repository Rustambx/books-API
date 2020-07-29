<?php
Route::group([
    'middleware' => 'api',
    'prefix' => 'api',
    'namespace' => 'App\Modules\Condition\Controllers',
], function () {

    Route::get('conditions', 'ConditionController@getTermsAndConditions');
});
