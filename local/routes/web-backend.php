<?php



Route::prefix('admin')->group(function () {

    Route::get('/', 'Backend\HomeController@home')->name('home');


    Route::get('customer_service/check_doc', function () {
        return view('backend.customer_service.check_doc.index');
    })->name('check_doc');
    Route::get('customer_service/info_customer', function () {
        return view('backend.customer_service.check_doc.info_customer');
    })->name('info_customer');



    // BEGIN member
    Route::get('member', 'Backend\MemberController@index')->name('member');
    Route::get('member/get_data_member', 'Backend\MemberController@get_data_member')->name('get_data_member');
    Route::post('member/store_member', 'Backend\MemberController@store_member')->name('store_member');
    Route::post('member/data_edit_password', 'Backend\MemberController@data_edit_password')->name('data_edit_password');
    Route::post('member/change_password_member', 'Backend\MemberController@change_password_member')->name('change_password_member');
    Route::post('member/delete_member', 'Backend\MemberController@delete_member')->name('delete_member');
    // END member
});
