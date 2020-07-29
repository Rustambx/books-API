<?php
Route::group([
    'middleware' => ['web', 'auth'],
    'prefix' => 'comment',
    'namespace' => 'App\Modules\Comment\Controllers',

], function () {
    Route::get('/list', 'CommentController@showList')->name('comment');
    Route::get('/show/{id}', 'CommentController@showDetail')->name('comment.show');
    Route::get('/add', 'CommentController@addForm')->name('comment.add');
    Route::post('save', 'CommentController@save')->name('comment.save');

    Route::get('/edit/{id}', 'CommentController@editComment')
        ->name('comment.edit');
    Route::delete('/delete/{id}', 'CommentController@deleteComment')
        ->name('comment.delete');

});
