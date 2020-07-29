<?php

Route::group([
    'middleware' => 'web',
    'prefix' => 'taxonomy'
], function () {

    Route::group([
        'middleware' => ['auth'],
        'namespace' => 'App\Modules\Taxonomy\Controllers',
    ], function () {

        /**
         * Vocabularies
         */
        Route::get('/', 'VocabularyController@showVocabularies')
            ->name('taxonomy');

        Route::get('/vocabulary/add', 'VocabularyController@addVocabulary')
            ->name('taxonomy.vocabulary.add');

        Route::get('/vocabulary/{vid}/edit', 'VocabularyController@editVocabulary')
            ->name('taxonomy.vocabulary.edit')
            ->where('vid', '[0-9]+');

        Route::post('vocabulary/save', 'VocabularyController@saveVocabulary')
            ->name('taxonomy.vocabulary.save');

        Route::delete('/vocabulary/delete/{id}', 'VocabularyController@deleteVocabulary')
            ->name('taxonomy.vocabulary.delete');

        /**
         * Terms
         */

        Route::get('/vocabulary/{id}/terms', 'TermController@showTerms')
            ->name('taxonomy.terms.list');

        Route::get('/vocabulary/{id}/term/add', 'TermController@addTerm')
            ->name('taxonomy.term.add');

        Route::get('/term/{tid}/edit', 'TermController@editTerm')
             ->name('taxonomy.term.edit')
            ->where('tid', '[0-9]+');

        Route::post('term/save', 'TermController@saveTerm')
             ->name('taxonomy.term.save');

        Route::delete('/term/delete/{id}', 'TermController@deleteTerm')
             ->name('taxonomy.term.delete');

    });

    Route::group([
        'middleware' => ['auth', 'permission:manage-taxonomy'],
        'namespace' => 'App\Modules\Taxonomy\Controllers',
        'prefix' => 'structure/taxonomy/api'
    ], function () {

        Route::post('/filter-vocabularies', 'WebApiController@filterVocabularies')
             ->name('taxonomy.web-api.filter-vocabularies');

        Route::post('/rebuild-tree', 'WebApiController@rebuildTree')
             ->name('taxonomy.web-api.rebuild-tree');

    });

});

Route::group([
    'middleware' => ['web', 'webapi'],
    'namespace' => 'App\Modules\Taxonomy\Controllers',
    'prefix' => '/web-api/taxonomy/'
], function () {

    Route::post('/get-categories', 'WebApiController@getCategories');

    Route::post('/get-sub-categories', 'WebApiController@getSubCategories');

    Route::post('/get-languages', 'WebApiController@getLanguages');

    Route::post('/get-term-data', 'WebApiController@getTermData');

    Route::post('/get-top-categories', 'WebApiController@getTopCategories');

});
