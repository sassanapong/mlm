<?php



Route::prefix('admin')->group(function () {

    Route::get('/', 'Backend\HomeController@home')->name('home');
});
