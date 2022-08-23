<?php

Route::get('/config-cache', function () {
  $exitCode = Artisan::call('cache:clear');
  $exitCode = Artisan::call('config:clear');
  $exitCode = Artisan::call('view:clear');
  // $exitCode = Artisan::call('config:cache');
  return back();
});



Route::get('/', function () {
    if(Auth::guard('c_user')->check()){
       return redirect('home');
     }else{
      return view('frontend/login');
     }
  });

  Route::get('logout', function () {
    Auth::guard('c_user')->logout();
    //Session::flush();
    return redirect('login');
  })->name('logout');

  Route::post('login','Frontend\LoginController@login')->name('login');
  Route::get('home','Frontend\HomeController@index')->name('home');
