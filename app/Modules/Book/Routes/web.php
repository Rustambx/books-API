<?php
Route::group([
    'middleware' => ['web', 'auth'],
    'prefix' => 'book',
    'namespace' => 'App\Modules\Book\Controllers',

], function () {
    Route::get('/list', 'BookController@showList')->name('book');
    Route::get('/add', 'BookController@addForm')->name('book.add');
    Route::post('save', 'BookController@save')->name('book.save');

    Route::get('/edit/{id}', 'BookController@editBook')
        ->name('book.edit');

    Route::get('/translate/{id}', 'BookController@translateBook')
        ->name('book.translate');

    Route::delete('/delete/{id}', 'BookController@deleteBook')
        ->name('book.delete');

});
