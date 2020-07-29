<?php
Route::group([
    'middleware' => 'api',
    'prefix' => 'api',
    'namespace' => 'App\Modules\Book\Controllers',
], function () {

    Route::get('books/featured', 'ApiController@getFeaturedBooks');
    Route::get('books/latest', 'ApiController@getLatestBooks');
    Route::get('books/categories', 'ApiController@getBooksByCategory');
    Route::get('book/comments/{id}', 'ApiController@getBookComments');
    Route::get('book/chapters/{id}', 'ApiController@getBookChapters');

    Route::post('book/comment', 'ApiController@postBookComment');
    Route::post('book/comment/like', 'ApiController@postLikeComment');
    Route::post('search', 'ApiController@postBooksBySearchQuery');
});
