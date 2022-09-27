<?php


Route::get('/admin', function () {
    if (Auth::guard('member')->check()) {
        return view('backend/index');
    } else {
        return view('frontend/login_admin');
    }
})->name('admin_home');

Route::prefix('admin')->group(function () {



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

    // BEGIN news
    Route::get('news_manage', 'Backend\NewsController@index')->name('news_manage');
    Route::post('news_manage/store', 'Backend\NewsController@store')->name('news_manage_store');
    Route::post('news_manage/edit', 'Backend\NewsController@edit')->name('news_manage_edit');
    Route::get('news_manage/edit_data', 'Backend\NewsController@Pulldata')->name('news_manage_edit_data');
    Route::get('news_manage/delete/{id}', 'Backend\NewsController@destroy')->name('news_manage_delete');
    // END news

    // BEGIN mdk learning
    Route::get('mdk_learning', 'Backend\Mdk_LearningController@index')->name('mdk_learning');
    Route::post('mdk_learning/store', 'Backend\Mdk_LearningController@store')->name('mdk_learning_store');
    Route::post('mdk_learning/edit', 'Backend\Mdk_LearningController@edit')->name('mdk_learning_edit');
    Route::get('mdk_learning/edit_data', 'Backend\Mdk_LearningController@Pulldata')->name('mdk_learning_edit_data');
    Route::get('mdk_learning/delete/{id}', 'Backend\Mdk_LearningController@destroy')->name('mdk_learning_delete');
    // END mdk learning

     // BEGIN mdk ct
     Route::get('mdk_ct', 'Backend\Mdk_CtController@index')->name('mdk_ct');
     Route::post('mdk_ct/store', 'Backend\Mdk_CtController@store')->name('mdk_ct_store');
     Route::post('mdk_ct/edit', 'Backend\Mdk_CtController@edit')->name('mdk_ct_edit');
     Route::get('mdk_ct/edit_data', 'Backend\Mdk_CtController@Pulldata')->name('mdk_ct_edit_data');
     Route::get('mdk_ct/delete/{id}', 'Backend\Mdk_CtController@destroy')->name('mdk_ct_delete');
     // END mdk ct

    // BEGIN Issue
    Route::get('Issue', 'Backend\IssueController@index')->name('Issue');
    Route::get('Issue/get_repost_issue', 'Backend\IssueController@get_repost_issue')->name('get_repost_issue');
    Route::post('Issue/get_data_info_issue', 'Backend\IssueController@get_data_info_issue')->name('get_data_info_issue');
    // END Issue
});
