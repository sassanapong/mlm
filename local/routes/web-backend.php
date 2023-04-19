<?php


Route::get('/admin', function () {
    if (Auth::guard('member')->check()) {
        return view('backend/index');
    } else {
        return view('frontend/login_admin');
    }
})->name('admin_home');



Route::prefix('admin')->group(function () {


    Route::get('getProvince', 'Backend\AddressController@getProvince')->name('admin_getProvince');
    Route::get('getDistrict', 'Backend\AddressController@getDistrict')->name('admin_getDistrict');
    Route::get('getTambon', 'Backend\AddressController@getTambon')->name('admin_getTambon');
    Route::get('getZipcode', 'Backend\AddressController@getZipcode')->name('admin_getZipcode');



    Route::get('check_doc', 'Backend\CustomerServiceController@index')->name('check_doc');
    Route::get('get_check_doc', 'Backend\CustomerServiceController@get_check_doc')->name('get_check_doc');
    Route::post('admin_get_info_card', 'Backend\CustomerServiceController@admin_get_info_card')->name('admin_get_info_card');
    Route::post('action_card_doc', 'Backend\CustomerServiceController@action_card_doc')->name('action_card_doc');
    Route::post('admin_get_info_bank', 'Backend\CustomerServiceController@admin_get_info_bank')->name('admin_get_info_bank');
    Route::post('action_bank_doc', 'Backend\CustomerServiceController@action_bank_doc')->name('action_bank_doc');


    Route::get('admin_login_user/{id}', 'Backend\CustomerServiceController@admin_login_user')->name('admin_login_user');
    Route::get('info_customer/{id}', 'Backend\CustomerServiceController@info_customer')->name('info_customer');
    Route::post('admin_search_username', 'Backend\CustomerServiceController@search_username')->name('search_username');

    Route::post('admin_edit_form_info', 'Backend\CustomerServiceController@admin_edit_form_info')->name('admin_edit_form_info');
    Route::post('admin_edit_form_info_card', 'Backend\CustomerServiceController@admin_edit_form_info_card')->name('admin_edit_form_info_card');
    Route::post('admin_edit_form_address_delivery', 'Backend\CustomerServiceController@admin_edit_form_address_delivery')->name('admin_edit_form_address_delivery');
    Route::post('admin_edit_form_info_bank', 'Backend\CustomerServiceController@admin_edit_form_info_bank')->name('admin_edit_form_info_bank');
    Route::post('admin_edit_form_info_benefit', 'Backend\CustomerServiceController@admin_edit_form_info_benefit')->name('admin_edit_form_info_benefit');


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
    Route::post('Issue/action_data_isseu', 'Backend\IssueController@action_data_isseu')->name('action_data_isseu');
    // END Issue

    // BEGIN Promotion_help
    Route::get('promotion_help', 'Backend\PromotionHelpController@index')->name('promotion_help');
    Route::get('get_promotion_help', 'Backend\PromotionHelpController@get_promotion_help')->name('get_promotion_help');
    Route::post('get_data_promotion_help', 'Backend\PromotionHelpController@get_data_promotion_help')->name('get_data_promotion_help');
    Route::post('action_data_promo_help', 'Backend\PromotionHelpController@action_data_promo_help')->name('action_data_promo_help');
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

    // BEGIN receive
    Route::get('takeout', 'Backend\TakeoutController@index')->name('takeout');
    Route::get('takeout/get_data_warehouse_select', 'Backend\TakeoutController@get_data_warehouse_select')->name('get_data_warehouse_select');
    Route::get('takeout/get_data_product_select', 'Backend\TakeoutController@get_data_product_select')->name('get_data_product_select');
    Route::post('takeout/takeout_product', 'Backend\TakeoutController@takeout_product')->name('takeout_product');
    Route::get('takeout/get_data_takeout', 'Backend\TakeoutController@get_data_takeout')->name('get_data_takeout');
    Route::post('takeout/get_data_matereials', 'Backend\TakeoutController@get_data_matereials')->name('get_data_matereials');
    Route::post('takeout/get_max_input_atm_takeout', 'Backend\TakeoutController@get_max_input_atm_takeout')->name('get_max_input_atm_takeout');
    Route::post('takeout/get_lot_number_takeout', 'Backend\TakeoutController@get_lot_number_takeout')->name('get_lot_number_takeout');
    Route::post('takeout/get_lot_expired_date', 'Backend\TakeoutController@get_lot_expired_date')->name('get_lot_expired_date');
    // END receive

    // BEGIN Stock_report
    Route::get('stock', 'Backend\StockController@index')->name('stock');
    Route::get('stock/get_data_stock_report', 'Backend\StockController@get_data_stock_report')->name('get_data_stock_report');
    // END Stock_report


    // BEGIN Stock_Card
    Route::get('stock/stockcard/{product_id_fk}/{branch_id_fk}/{warehouse_id_fk}/{lot_expired_date}/{lot_number}', 'Backend\StockCardController@index')->name('stockcard');
    Route::get('stock/stockcard/get_stock_card', 'Backend\StockCardController@get_stock_card')->name('get_stock_card');

    // END Stock_Card


    // BEGIN eWallet
    Route::get('eWallet', 'Backend\eWalletController@index')->name('eWallet');
    Route::get('withdraw', 'Backend\eWalletController@withdraw')->name('withdraw');
    Route::get('transfer', 'Backend\eWalletController@transfer')->name('transfer');
    Route::get('export', 'Backend\eWalletController@export')->name('export');
    Route::get('export2', 'Backend\eWalletController@export2')->name('export2');
    Route::post('import', 'Backend\eWalletController@import')->name('import');
    Route::get('eWallet/get_ewallet', 'Backend\eWalletController@get_ewallet')->name('get_ewallet');
    Route::get('eWallet/get_transfer', 'Backend\eWalletController@get_transfer')->name('get_transfer');
    Route::get('eWallet/get_withdraw', 'Backend\eWalletController@get_withdraw')->name('get_withdraw');
    Route::post('eWallet/get_info_ewallet', 'Backend\eWalletController@get_info_ewallet')->name('get_info_ewallet');

    Route::post('eWallet/approve_update_ewallet', 'Backend\eWalletController@approve_update_ewallet')->name('approve_update_ewallet');
    Route::post('eWallet/disapproved_update_ewallet', 'Backend\eWalletController@disapproved_update_ewallet')->name('disapproved_update_ewallet');

    // EDN eWallet

    // BEGIN Order
    Route::get('orders/list', 'Backend\OrderController@orders_list')->name('orders_list');
    Route::get('orders/success', 'Backend\OrderController@orders_success')->name('orders_success');
    Route::post('orders/tracking_no', 'Backend\OrderController@tracking_no')->name('tracking_no');
    Route::get('orders/get_data_order_list', 'Backend\OrderController@get_data_order_list')->name('get_data_order_list');
    Route::get('orders/get_data_order_list_success', 'Backend\OrderController@get_data_order_list_success')->name('get_data_order_list_success');
    Route::get('orderexport/{date_start}/{date_end}', 'Backend\OrderController@orderexport')->name('orderexport');
    Route::post('importorder', 'Backend\OrderController@importorder')->name('importorder');
    Route::get('orders/view_detail_oeder/{code_order}', 'Backend\OrderController@view_detail_oeder')->name('view_detail_oeder');
    Route::get('orders/report_order_pdf/{shipping_type}/{date_start}/{date_end}', 'Backend\OrderController@report_order_pdf')->name('report_order_pdf');
    Route::post('orders/tracking_no_sort', 'Backend\OrderController@tracking_no_sort')->name('tracking_no_sort');
    Route::post('orders/view_detail_oeder_pdf/', 'Backend\OrderController@view_detail_oeder_pdf')->name('view_detail_oeder_pdf');

    // END Order

    Route::get('ReportOrders', 'Backend\ReportOrdersController@index')->name('ReportOrders');
    Route::get('order_report_datable', 'Backend\ReportOrdersController@order_report_datable')->name('order_report_datable');


    Route::get('ReportWallet', 'Backend\ReportWalletController@index')->name('ReportWallet');
    Route::get('wallet_report_datable', 'Backend\ReportWalletController@wallet_report_datable')->name('wallet_report_datable');

    Route::get('ReporJangPV', 'Backend\ReportJangPVController@index')->name('ReporJangPV');
    Route::get('jangpv_report_datable', 'Backend\ReportJangPVController@jangpv_report_datable')->name('jangpv_report_datable');

    Route::get('CustomerAll', 'Backend\CustomerAllController@index')->name('CustomerAll');
    Route::get('customer_all_datable', 'Backend\CustomerAllController@customer_all_datable')->name('customer_all_datable');
    Route::post('update_position', 'Backend\CustomerAllController@update_position')->name('update_position');

    Route::get('log_uplavel', 'Backend\LogUplavelController@index')->name('log_uplavel');
    Route::get('log_uplavel_report_datable', 'Backend\LogUplavelController@log_uplavel_report_datable')->name('log_uplavel_report_datable');

    Route::get('report_xvvip', 'Backend\ReportXvvipController@index')->name('report_xvvip');
    Route::get('report_xvvip_report_datable', 'Backend\ReportXvvipController@report_xvvip_report_datable')->name('report_xvvip_report_datable');

    Route::get('report_warning_copyright', 'Backend\ReportWarningCopyrightController@index')->name('report_warning_copyright');
    Route::get('report_warning_copyright_datable', 'Backend\ReportWarningCopyrightController@report_warning_copyright_datable')->name('report_warning_copyright_datable');

    Route::get('report_copyright', 'Backend\ReportCopyrightController@index')->name('report_copyright');
    Route::get('report_copyright_datable', 'Backend\ReportCopyrightController@report_copyright_datable')->name('report_copyright_datable');




    Route::get('bonus_active_report', 'Backend\BonusActiveReportController@index')->name('bonus_active_report');
    Route::get('bonus_active_report_datable', 'Backend\BonusActiveReportController@bonus_active_report_datable')->name('bonus_active_report_datable');

    Route::get('easy_report', 'Backend\EasyReportReportController@index')->name('easy_report');
    Route::get('easy_report_datable', 'Backend\EasyReportReportController@easy_report_datable')->name('easy_report_datable');

    Route::get('allsale_report', 'Backend\AllsaleReportControlle@index')->name('allsale_report');
    Route::get('allsale_report_datable', 'Backend\AllsaleReportControlle@allsale_report_datable')->name('allsale_report_datable');

    Route::get('cashback_report', 'Backend\CashBackReportControlle@index')->name('cashback_report');
    Route::get('cashback_report_datable', 'Backend\CashBackReportControlle@cashback_report_datable')->name('cashback_report_datable');



    Route::get('shipping_location', 'Backend\ShippingLocationtControlle@index')->name('shipping_location');
    Route::get('shipping_location_datable', 'Backend\ShippingLocationtControlle@shipping_location_datable')->name('shipping_location_datable');
    Route::post('add_shipping_location', 'Backend\ShippingLocationtControlle@add_shipping_location')->name('add_shipping_location');



    // materials
    Route::get('materials', 'Backend\MatreialsController@index')->name('materials');
    Route::post('materials/store_materials', 'Backend\MatreialsController@store_materials')->name('store_materials');
    Route::post('materials/get_materials', 'Backend\MatreialsController@get_materials')->name('get_materials');
    Route::post('materials/update_materials', 'Backend\MatreialsController@update_materials')->name('update_materials');
});
