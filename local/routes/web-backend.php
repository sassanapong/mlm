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

    Route::get('customer_service/admin_login_user/{id}', 'Backend\CustomerServiceController@admin_login_user')->name('admin_login_user');


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

    // BEGIN Promotion_help
    Route::get('promotion_help', 'Backend\PromotionHelpController@index')->name('promotion_help');
    Route::get('get_promotion_help', 'Backend\PromotionHelpController@get_promotion_help')->name('get_promotion_help');
    Route::post('get_data_promotion_help', 'Backend\PromotionHelpController@get_data_promotion_help')->name('get_data_promotion_help');
    // END Promotion_help



    // BEGIN Branch
    Route::get('branch', 'Backend\BranchController@index')->name('branch');
    Route::post('store_branch', 'Backend\BranchController@store_branch')->name('store_branch');
    Route::get('get_data_branch', 'Backend\BranchController@get_data_branch')->name('get_data_branch');
    Route::post('get_data_info_branch', 'Backend\BranchController@get_data_info_branch')->name('get_data_info_branch');
    Route::post('update_branch', 'Backend\BranchController@update_branch')->name('update_branch');
    // END Branch


    // BEGIN Warehouse
    Route::get('branch/warehouse/{id}', 'Backend\WarehouseController@index')->name('warehouse');
    Route::post('store_warehoues', 'Backend\WarehouseController@store_warehoues')->name('store_warehoues');
    Route::get('get_data_warehouse', 'Backend\WarehouseController@get_data_warehouse')->name('get_data_warehouse');
    Route::post('get_data_info_warehouse', 'Backend\WarehouseController@get_data_info_warehouse')->name('get_data_info_warehouse');
    Route::post('update_warehouse', 'Backend\WarehouseController@update_warehouse')->name('update_warehouse');
    // END Warehouse

    // BEGIN receive
    Route::get('receive', 'Backend\ReceiveController@index')->name('receive');
    Route::get('receive/get_data_warehouse_select', 'Backend\ReceiveController@get_data_warehouse_select')->name('get_data_warehouse_select');
    Route::get('receive/get_data_product_select', 'Backend\ReceiveController@get_data_product_select')->name('get_data_product_select');
    Route::post('receive/store_product', 'Backend\ReceiveController@store_product')->name('store_product');
    Route::get('receive/get_data_receive', 'Backend\ReceiveController@get_data_receive')->name('get_data_receive');
    Route::get('receive/get_data_product_unit', 'Backend\ReceiveController@get_data_product_unit')->name('get_data_product_unit');
    // END receive

    // BEGIN Stock_report
    Route::get('stock', 'Backend\StockController@index')->name('stock');
    Route::get('stock/get_data_stock_report', 'Backend\StockController@get_data_stock_report')->name('get_data_stock_report');
    // END Stock_report


    // BEGIN Stock_Card
    Route::get('stock/stockcard/{product_id_fk}/{branch_id_fk}/{warehouse_id_fk}', 'Backend\StockCardController@index')->name('stockcard');
    Route::get('stock/stockcard/get_stock_card', 'Backend\StockCardController@get_stock_card')->name('get_stock_card');

    // END Stock_Card
});
