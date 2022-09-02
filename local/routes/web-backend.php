<?php



Route::prefix('admin')->group(function () {

    Route::get('/', 'Backend\HomeController@home')->name('home');


    Route::get('customer_service/check_doc', function () {
        return view('backend.customer_service.check_doc.index');
    })->name('check_doc');
});
