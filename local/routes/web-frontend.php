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
       return redirect('frontend/home');
     }else{
      return view('frontend/login');
     }
  });

