<?php

Route::get('/config-cache', function () {
  $exitCode = Artisan::call('cache:clear');
  $exitCode = Artisan::call('config:clear');
  $exitCode = Artisan::call('view:clear');


  // $exitCode = Artisan::call('config:cache');
  return back();
});



Route::get('/', function () {

  if (Auth::guard('c_user')->check()) {

    return redirect('home');
  } else {

    return view('frontend/login');
  }
});

Route::get('login', function () {
  if (Auth::guard('c_user')->check()) {
    return redirect('home');
  } else {
    return view('frontend/login');
  }
});

Route::get('logout', function () {
  Auth::guard('c_user')->logout();
  Auth::guard('admin')->logout();
  //Session::flush();
  return redirect('/');
})->name('logout');


Route::get('lang/change', 'Frontend\HomeController@change')->name('changeLang');

Route::post('login', 'Frontend\LoginController@login')->name('login')
  ->middleware('prevent-repeated-clicks');
Route::post('admin_login', 'Frontend\LoginController@admin_login')->name('admin_login');


Route::get('tree', 'Frontend\TreeController@index')->name('tree');
Route::get('modal_tree', 'Frontend\TreeController@modal_tree')->name('modal_tree');
Route::post('tree_view', 'Frontend\TreeController@index')->name('tree_view');
Route::post('tree', 'Frontend\TreeController@index_post')->name('tree');



Route::get('RunError', 'Frontend\FC\RunErrorController@index')->name('RunError');
Route::get('home', 'Frontend\HomeController@index')->name('home');
// BEGIN หน้า Regisert
Route::get('register/{upline_id?}/{type?}', 'Frontend\RegisterController@index')->name('register');

Route::get('check_sponser', 'Frontend\RegisterController@check_sponser')->name('check_sponser');

Route::post('store_register', 'Frontend\RegisterController@store_register')->name('store_register')
  ->middleware('prevent-repeated-clicks');

Route::post('pv', 'Frontend\RegisterController@pv')->name('pv');
// END หน้า Regisert

// BEGIN หน้า upgradePosition
Route::get('upgradePosition', 'Frontend\PositionController@index')->name('upgradePosition');
// END หน้า upgradePosition

// BEGIN หน้า Workline
Route::get('Workline', 'Frontend\WorklineController@index')->name('Workline');
Route::get('Workline/{user_name?}/{lv?}', 'Frontend\WorklineController@index')->name('Workline');
Route::get('Workline_datatable', 'Frontend\WorklineController@datatable')->name('Workline_datatable');
// END หน้า Workline

// BEGIN หน้า Profile
Route::get('editprofile', 'Frontend\ProfileController@edit_profile')->name('editprofile');
Route::get('editprofileimg', 'Frontend\ProfileController@editprofileimg')->name('editprofileimg');

Route::post('update_img_profile', 'Frontend\ProfileController@update_img_profile')->name('update_img_profile');




Route::post('change_password', 'Frontend\ProfileController@change_password')->name('change_password');
Route::post('update_customers_info', 'Frontend\ProfileController@update_customers_info')->name('update_customers_info');
Route::post('update_same_address', 'Frontend\ProfileController@update_same_address')->name('update_same_address');
Route::post('cerate_info_bank_last', 'Frontend\ProfileController@cerate_info_bank_last')->name('cerate_info_bank_last');
Route::post('form_update_info_card', 'Frontend\ProfileController@form_update_info_card')->name('form_update_info_card');

// BEGIN API Address
Route::get('/getProvince', 'Frontend\AddressController@getProvince')->name('getProvince');
Route::get('/getDistrict', 'Frontend\AddressController@getDistrict')->name('getDistrict');
Route::get('/getTambon', 'Frontend\AddressController@getTambon')->name('getTambon');
Route::get('/getZipcode', 'Frontend\AddressController@getZipcode')->name('getZipcode');
// END API Address

// END หน้า Profile


// BEGIN หน้า Order
Route::get('Order', 'Frontend\OrderController@index')->name('Order');
Route::get('add_cart', 'Frontend\OrderController@add_cart')->name('add_cart');

Route::get('order_history', 'Frontend\OrderHistoryController@index')->name('order_history');

Route::get('history_datable', 'Frontend\OrderHistoryController@history_datable')->name('history_datable');
Route::get('order_detail/{code_order}', 'Frontend\OrderController@order_detail')->name('order_detail');


Route::get('cart', 'Frontend\OrderController@cart')->name('cart');
Route::post('cart_delete', 'Frontend\OrderController@cart_delete')->name('cart_delete');
Route::get('get_product', 'Frontend\OrderController@get_product')->name('get_product');
Route::post('quantity_change', 'Frontend\OrderController@quantity_change')->name('quantity_change');
Route::get('cancel_order', 'Frontend\OrderController@cancel_order')->name('cancel_order');

Route::get('confirm_cart', 'Frontend\ConfirmCartController@index')->name('confirm_cart');

Route::post('check_custome_unline', 'Frontend\ConfirmCartController@check_custome_unline')->name('check_custome_unline');

Route::post('payment_submit', 'Frontend\ConfirmCartController@payment_submit')->name('payment_submit')
  ->middleware('prevent-repeated-clicks');


Route::post('search', 'Frontend\TreeController@search')->name('search');
Route::post('under_a', 'Frontend\TreeController@under_a')->name('under_a');
Route::post('under_b', 'Frontend\TreeController@under_b')->name('under_b');
Route::get('home_check_customer_id', 'Frontend\TreeController@home_check_customer_id')->name('home_check_customer_id');


Route::get('RegisterUrlSetting', 'Frontend\RegisterUrlController@index')->name('RegisterUrlSetting');
Route::get('RegisterUrl/{user_name?}', 'Frontend\RegisterUrlController@register_url')->name('RegisterUrl');
Route::post('url_store_register', 'Frontend\RegisterUrlController@store_register')->name('url_store_register')
  ->middleware('prevent-repeated-clicks');
// END หน้า Order

// BEGIN หน้าLearning
Route::get('Learning', 'Frontend\LearningController@index')->name('Learning');
Route::get('search_lrn', 'Frontend\LearningController@search_lrn')->name('search_lrn');
Route::get('learning_detail/{id}', 'Frontend\LearningController@learning_detail')->name('learning_detail');
Route::get('ct', 'Frontend\LearningController@ct')->name('ct');
Route::get('search_ct', 'Frontend\LearningController@search_ct')->name('search_ct');
Route::get('ct_detail/{id}', 'Frontend\LearningController@ct_detail')->name('ct_detail');

// END หน้า Learning

// BEGIN หน้า Contact
Route::get('Contact', 'Frontend\ContactController@index')->name('Contact');
Route::post('store_report_issue', 'Frontend\ContactController@store_report_issue')->name('store_report_issue')
  ->middleware('prevent-repeated-clicks');
Route::post('store_promotion_help', 'Frontend\ContactController@store_promotion_help')->name('store_promotion_help')
  ->middleware('prevent-repeated-clicks');

// END หน้า Contact


// BEGIN หน้า JP
Route::get('jp_clarify', 'Frontend\JPController@jp_clarify')->name('jp_clarify');
Route::post('jang_pv_cash_back', 'Frontend\JPController@jang_pv_cash_back')->name('jang_pv_cash_back')
  ->middleware('prevent-repeated-clicks'); //cash_back
Route::post('jang_pv_active', 'Frontend\JPController@jang_pv_active')->name('jang_pv_active')
  ->middleware('prevent-repeated-clicks');

Route::post('tranfer_pv', 'Frontend\JPController@tranfer_pv')->name('tranfer_pv')
  ->middleware('prevent-repeated-clicks');
Route::post('jang_pv_upgrad', 'Frontend\JPController@jang_pv_upgrad')->name('jang_pv_upgrad')
  ->middleware('prevent-repeated-clicks');

Route::get('jp_transfer', 'Frontend\JPController@jp_transfer')->name('jp_transfer');
Route::get('jangpv_datatable', 'Frontend\JPController@datatable')->name('jangpv_datatable');

Route::get('checkcustomer_upline_upgrad', 'Frontend\JPController@checkcustomer_upline_upgrad')->name('checkcustomer_upline_upgrad');



// END หน้า JP

// BEGIN หน้า eWallet
Route::get('eWallet_history', 'Frontend\eWalletController@eWallet_history')->name('eWallet_history');
Route::get('eWallet_history/front_end_get_ewallet', 'Frontend\eWalletController@front_end_get_ewallet')->name('front_end_get_ewallet');


Route::get('eWallet-TranferHistory', 'Frontend\eWallet_tranferController@index')->name('eWallet-TranferHistory');

Route::get('eWallet_TranferHistory_table', 'Frontend\eWallet_tranferController@eWallet_TranferHistory_table')->name('eWallet_TranferHistory_table');


// END หน้า  eWallet


// BEGIN หน้า Bonus
Route::get('bonus_all', 'Frontend\BonusController@bonus_all')->name('bonus_all');
Route::get('bonus-ws', 'Frontend\BonusController@bonusws')->name('bonus-ws');
Route::get('reportsws', 'Frontend\BonusController@reportsws')->name('reportsws');

Route::get('bonusws_datatable', 'Frontend\BonusController@bonusws_datatable')->name('bonusws_datatable');
Route::get('reportsws_datatable', 'Frontend\BonusController@reportsws_datatable')->name('reportsws_datatable');

Route::get('bonus_fastStart', 'Frontend\BonusController@bonus_fastStart')->name('bonus_fastStart');
Route::get('bonus_team', 'Frontend\BonusController@bonus_team')->name('bonus_team');
Route::get('bonus_discount', 'Frontend\BonusController@bonus_discount')->name('bonus_discount');
Route::get('bonus_matching', 'Frontend\BonusController@bonus_matching')->name('bonus_matching');
Route::get('bonus_history', 'Frontend\BonusController@bonus_history')->name('bonus_history');
// END หน้า  Bonus

// BEGIN หน้า News
Route::get('news_detail/{id}', 'Frontend\NewsController@news_detail')->name('news_detail');
// END หน้า  News

// BEGIN eWallet deposit
Route::post('home/deposit/', 'Frontend\eWalletController@deposit')->name('deposit')
  ->middleware('prevent-repeated-clicks');
// BEGIN eWallet deposit

// BEGIN eWallet transfer
Route::post('home/transfer/', 'Frontend\eWalletController@transfer')->name('frontendtransfer')
  ->middleware('prevent-repeated-clicks');
Route::post('/checkcustomer', 'Frontend\eWalletController@checkcustomer')->name('checkcustomer');
Route::get('checkcustomer_upline', 'Frontend\eWalletController@checkcustomer_upline')->name('checkcustomer_upline');
Route::get('checkcustomer_upline_tranfer_pv', 'Frontend\eWalletController@checkcustomer_upline_tranfer_pv')->name('checkcustomer_upline_tranfer_pv');
Route::post('/check_customerbank', 'Frontend\eWalletController@check_customerbank')->name('check_customerbank')
  ->middleware('prevent-repeated-clicks');

// BEGIN eWallet transfer

// BEGIN eWallet withdraw
Route::post('home/withdraw/', 'Frontend\eWalletController@withdraw')->name('frontendwithdraw')
  ->middleware('prevent-repeated-clicks');

Route::get('fc_shipping_zip_code_js', 'Frontend\ShippingController@fc_shipping_zip_code_js')->name('fc_shipping_zip_code_js');

Route::get('expire_180', 'Frontend\FC\RunPerDayPerMonthController@expire_180')->name('expire_180');
Route::get('run_report_pv_ewallet', 'Frontend\FC\RunPerDayPerMonthController@run_report_pv_ewallet')->name('run_report_pv_ewallet');

Route::get('RunbonusPerday', 'Frontend\FC\RunPerDayPerMonthController@RunbonusPerday')->name('RunbonusPerday');

Route::get('Runbonus_all_ewallet', 'Frontend\FC\RunPerDayPerMonthController@Runbonus_all_ewallet')->name('Runbonus_all_ewallet');

Route::get('bonus_allsale_permounth_check/{user_check}', 'Frontend\FC\RunPerDayPerMonth_allsale_checkController@bonus_allsale_permounth_check')->name('bonus_allsale_permounth_check');
Route::get('bonus_allsale_permounth_check2/{user_check}', 'Frontend\FC\RunPerDayPerMonth_allsale_checkController@bonus_allsale_permounth_check_2')->name('bonus_allsale_permounth_check2');

Route::get('bonus_allsale_permounth_01', 'Frontend\FC\RunPerDayPerMonth_orsale_01Controller@bonus_allsale_permounth_01')->name('bonus_allsale_permounth_01');
Route::get('bonus_allsale_permounth_02', 'Frontend\FC\RunPerDayPerMonth_orsale_01Controller@bonus_allsale_permounth_02')->name('bonus_allsale_permounth_02');
Route::get('bonus_allsale_permounth_03', 'Frontend\FC\RunPerDayPerMonth_orsale_03Controller@bonus_allsale_permounth_03')->name('bonus_allsale_permounth_03');
Route::get('bonus_allsale_permounth_04', 'Frontend\FC\RunPerDayPerMonth_orsale_03Controller@bonus_allsale_permounth_04')->name('bonus_allsale_permounth_04');
Route::get('bonus_allsale_permounth_05', 'Frontend\FC\RunPerDayPerMonth_orsale_03Controller@bonus_allsale_permounth_05')->name('bonus_allsale_permounth_05');
Route::get('bonus_allsale_permounth_06', 'Frontend\FC\RunPerDayPerMonth_orsale_03Controller@bonus_allsale_permounth_06')->name('bonus_allsale_permounth_06');



// Route::get('update_intro', 'Frontend\FC2024\NewUplineFunctionController@update_intro')->name('update_intro');
// Route::get('delete_empty_upline', 'Frontend\FC2024\NewUplineFunctionController@delete_empty_upline')->name('delete_empty_upline');
Route::get('check_all_intro/{username_check}', 'Frontend\FC2024\NewUplineFunctionController@check_all_upline')->name('check_all_intro');
Route::get('check_all_upline/{username}', 'Frontend\FC2024\NewUpline3ABFunctionController@allupline')->name('check_all_upline');


Route::get('uplineAB', 'Frontend\FC2024\NewUpline2ABFunctionController@uplineAB')->name('uplineAB');


// Route::get('set_pv_upgrade', 'Frontend\FC2024\NewUplineFunctionController@set_pv_upgrade')->name('set_pv_upgrade');

Route::get('RunbonusPerday2024', 'Frontend\FC2024\RunPerDay_pv_ab01Controller@RunbonusPerday')->name('RunbonusPerday2024')
  ->middleware('prevent-repeated-clicks');
Route::get('Runbonus8Perday', 'Frontend\FC2024\RunPerDay_pv_ab02Controller@Runbonus4Perday')->name('Runbonus8Perday')
  ->middleware('prevent-repeated-clicks');

Route::get('Runbonus8PerdayEwarlet', 'Frontend\FC2024\RunPerDay_pv_ab02Controller@bonus_4_03')
  ->name('Runbonus8PerdayEwarlet')
  ->middleware('prevent-repeated-clicks');

Route::get('Runbonus9Perday', 'Frontend\FC2024\RunPerDay_pv_ab03Controller@Runbonus9Perday')->name('Runbonus9Perday')
  ->middleware('prevent-repeated-clicks');
Route::get('check_introduce_id/{username}', 'Frontend\FC2024\RunPerDay_pv_ab03Controller@check_introduce_id')->name('check_introduce_id')
  ->middleware('prevent-repeated-clicks');
Route::get('bonus_9_ewallet', 'Frontend\FC2024\RunPerDay_pv_ab03Controller@bonus_9_ewallet')->name('bonus_9_ewallet')
  ->middleware('prevent-repeated-clicks');



Route::get('bonus_7_all/{code}', 'Frontend\FC2024\RunPerDay_pv_ab04Controller@bonus_7_all')->name('bonus_7_all')
  ->middleware('prevent-repeated-clicks');


Route::get('bonus_7_ewallet', 'Frontend\FC2024\RunPerDay_pv_ab04Controller@bonus_7_ewallet')->name('bonus_7_ewallet')
  ->middleware('prevent-repeated-clicks');
  

// BEGIN eWallet withdraw
