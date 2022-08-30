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

Route::get('logout', function () {
  Auth::guard('c_user')->logout();
  //Session::flush();
  return redirect('/');
})->name('logout');



Route::post('login', 'Frontend\LoginController@login')->name('login');




Route::get('home', 'Frontend\HomeController@index')->name('home');
// BEGIN หน้า Regisert
Route::get('register', 'Frontend\RegisterController@index')->name('register');
Route::post('store_register', 'Frontend\RegisterController@store_register')->name('store_register');
// END หน้า Regisert

// BEGIN หน้า upgradePosition
Route::get('upgradePosition', 'Frontend\PositionController@index')->name('upgradePosition');
// END หน้า upgradePosition

// BEGIN หน้า Workline
Route::get('Workline', 'Frontend\WorklineController@index')->name('Workline');
// END หน้า Workline

// BEGIN หน้า Profile
Route::get('editprofile', 'Frontend\ProfileController@edit_profile')->name('editprofile');

Route::post('change_password', 'Frontend\ProfileController@change_password')->name('change_password');
Route::post('update_customers_info', 'Frontend\ProfileController@update_customers_info')->name('update_customers_info');
Route::post('update_same_address', 'Frontend\ProfileController@update_same_address')->name('update_same_address');
Route::post('cerate_info_bank_last', 'Frontend\ProfileController@cerate_info_bank_last')->name('cerate_info_bank_last');

// BEGIN API Address
Route::get('/getProvince', 'Frontend\AddressController@getProvince')->name('getProvince');
Route::get('/getDistrict', 'Frontend\AddressController@getDistrict')->name('getDistrict');
Route::get('/getTambon', 'Frontend\AddressController@getTambon')->name('getTambon');
Route::get('/getZipcode', 'Frontend\AddressController@getZipcode')->name('getZipcode');
// END API Address

// END หน้า Profile


// BEGIN หน้า Order
Route::get('Order', 'Frontend\OrderController@index')->name('Order');

Route::get('order_history', 'Frontend\OrderController@order_history')->name('order_history');

Route::get('order_detail', 'Frontend\OrderController@order_detail')->name('order_detail');

// END หน้า Order

// BEGIN หน้าLearning
Route::get('Learning', 'Frontend\LearningController@index')->name('Learning');
Route::get('learning_detail', 'Frontend\LearningController@learning_detail')->name('learning_detail');
Route::get('ct', 'Frontend\LearningController@ct')->name('ct');
Route::get('ct_detail', 'Frontend\LearningController@ct_detail')->name('ct_detail');

// END หน้า Learning

// BEGIN หน้า Contact
Route::get('Contact', 'Frontend\ContactController@index')->name('Contact');
// END หน้า Contact


// BEGIN หน้า JP
Route::get('jp_clarify', 'Frontend\JPController@jp_clarify')->name('jp_clarify');
Route::get('jp_transfer', 'Frontend\JPController@jp_transfer')->name('jp_transfer');
// END หน้า JP

// BEGIN หน้า eWallet
Route::get('eWallet_history', 'Frontend\eWalletController@eWallet_history')->name('eWallet_history');
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
Route::get('news_detail', 'Frontend\NewsController@news_detail')->name('news_detail');
  // END หน้า  News