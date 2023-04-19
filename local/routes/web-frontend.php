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
  Auth::guard('member')->logout();
  //Session::flush();
  return redirect('/');
})->name('logout');


Route::get('lang/change', 'Frontend\HomeController@change')->name('changeLang');

Route::post('login', 'Frontend\LoginController@login')->name('login');
Route::post('admin_login', 'Frontend\LoginController@admin_login')->name('admin_login');


Route::get('tree', 'Frontend\TreeController@index')->name('tree');
Route::get('modal_tree', 'Frontend\TreeController@modal_tree')->name('modal_tree');
Route::post('tree_view', 'Frontend\TreeController@index')->name('tree_view');

Route::get('RunError', 'Frontend\FC\RunErrorController@index')->name('RunError');
Route::get('home', 'Frontend\HomeController@index')->name('home');
// BEGIN หน้า Regisert
Route::get('register', 'Frontend\RegisterController@index')->name('register');
Route::get('check_sponser', 'Frontend\RegisterController@check_sponser')->name('check_sponser');

Route::post('store_register', 'Frontend\RegisterController@store_register')->name('store_register');

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

Route::post('payment_submit', 'Frontend\ConfirmCartController@payment_submit')->name('payment_submit');



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
Route::post('store_report_issue', 'Frontend\ContactController@store_report_issue')->name('store_report_issue');
Route::post('store_promotion_help', 'Frontend\ContactController@store_promotion_help')->name('store_promotion_help');

// END หน้า Contact


// BEGIN หน้า JP
Route::get('jp_clarify', 'Frontend\JPController@jp_clarify')->name('jp_clarify');
Route::post('jang_pv_cash_back', 'Frontend\JPController@jang_pv_cash_back')->name('jang_pv_cash_back'); //cash_back
Route::post('jang_pv_active', 'Frontend\JPController@jang_pv_active')->name('jang_pv_active');

Route::post('tranfer_pv', 'Frontend\JPController@tranfer_pv')->name('tranfer_pv');
Route::post('jang_pv_upgrad', 'Frontend\JPController@jang_pv_upgrad')->name('jang_pv_upgrad');

Route::get('jp_transfer', 'Frontend\JPController@jp_transfer')->name('jp_transfer');
Route::get('jangpv_datatable', 'Frontend\JPController@datatable')->name('jangpv_datatable');

Route::get('checkcustomer_upline_upgrad', 'Frontend\JPController@checkcustomer_upline_upgrad')->name('checkcustomer_upline_upgrad');



// END หน้า JP

// BEGIN หน้า eWallet
Route::get('eWallet_history', 'Frontend\eWalletController@eWallet_history')->name('eWallet_history');
Route::get('eWallet_history/front_end_get_ewallet', 'Frontend\eWalletController@front_end_get_ewallet')->name('front_end_get_ewallet');
// END หน้า  eWallet

// BEGIN หน้า Bonus
Route::get('bonus_all', 'Frontend\BonusController@bonus_all')->name('bonus_all');
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
Route::post('home/deposit/', 'Frontend\eWalletController@deposit')->name('deposit');
// BEGIN eWallet deposit

// BEGIN eWallet transfer
Route::post('home/transfer/', 'Frontend\eWalletController@transfer')->name('frontendtransfer');
Route::post('/checkcustomer', 'Frontend\eWalletController@checkcustomer')->name('checkcustomer');
Route::get('checkcustomer_upline', 'Frontend\eWalletController@checkcustomer_upline')->name('checkcustomer_upline');
Route::get('checkcustomer_upline_tranfer_pv', 'Frontend\eWalletController@checkcustomer_upline_tranfer_pv')->name('checkcustomer_upline_tranfer_pv');
Route::post('/check_customerbank', 'Frontend\eWalletController@check_customerbank')->name('check_customerbank');

// BEGIN eWallet transfer

// BEGIN eWallet withdraw
Route::post('home/withdraw/', 'Frontend\eWalletController@withdraw')->name('frontendwithdraw');

Route::get('fc_shipping_zip_code_js', 'Frontend\ShippingController@fc_shipping_zip_code_js')->name('fc_shipping_zip_code_js');


// BEGIN eWallet withdraw
