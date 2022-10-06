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

    // BEGIN product
    Route::get('product', 'Backend\ProductController@index')->name('product');
    Route::post('product/store', 'Backend\ProductController@store')->name('product_store');
    Route::post('product/edit', 'Backend\ProductController@edit')->name('product_edit');
    Route::get('product/edit_data', 'Backend\ProductController@Pulldata')->name('product_edit_data');
    Route::get('product/delete/{id}', 'Backend\ProductController@destroy')->name('product_delete');
    Route::post('product/get/slide', 'Backend\ProductController@get_slide_product');
    Route::post('product/slide/update', 'Backend\ProductController@slide_update');
    // END product

    // BEGIN product category
    Route::get('product_category', 'Backend\Product_CategoryController@index')->name('product_category');
    Route::post('product_category/store', 'Backend\Product_CategoryController@store')->name('product_category_store');
    Route::post('product_category/edit', 'Backend\Product_CategoryController@edit')->name('product_category_edit');
    Route::get('product_category/edit_data', 'Backend\Product_CategoryController@Pulldata')->name('product_category_edit_data');
    Route::get('product_category/delete/{id}', 'Backend\Product_CategoryController@destroy')->name('product_category_delete');
    // END product category

    // BEGIN product unit
    Route::get('product_unit', 'Backend\Product_UnitController@index')->name('product_unit');
    Route::post('product_unit/store', 'Backend\Product_UnitController@store')->name('product_unit_store');
    Route::post('product_unit/edit', 'Backend\Product_UnitController@edit')->name('product_unit_edit');
    Route::get('product_unit/edit_data', 'Backend\Product_UnitController@Pulldata')->name('product_unit_edit_data');
    Route::get('product_unit/delete/{id}', 'Backend\Product_UnitController@destroy')->name('product_unit_delete');
    // END product unit

    // BEGIN product size
    Route::get('product_size', 'Backend\Product_SizeController@index')->name('product_size');
    Route::post('product_size/store', 'Backend\Product_SizeController@store')->name('product_size_store');
    Route::post('product_size/edit', 'Backend\Product_SizeController@edit')->name('product_size_edit');
    Route::get('product_size/edit_data', 'Backend\Product_SizeController@Pulldata')->name('product_size_edit_data');
    Route::get('product_size/delete/{id}', 'Backend\Product_SizeController@destroy')->name('product_size_delete');
    // END product size

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


    // BEGIN eWallet
    Route::get('eWallet', 'Backend\eWalletController@index')->name('eWallet');
    Route::get('eWallet/get_ewallet', 'Backend\eWalletController@get_ewallet')->name('get_ewallet');
    Route::post('eWallet/get_info_ewallet', 'Backend\eWalletController@get_info_ewallet')->name('get_info_ewallet');


    Route::post('eWallet/approve_update_ewallet', 'Backend\eWalletController@approve_update_ewallet')->name('approve_update_ewallet');
    Route::post('eWallet/disapproved_update_ewallet', 'Backend\eWalletController@disapproved_update_ewallet')->name('disapproved_update_ewallet');


    // BEGIN eWallet
});
