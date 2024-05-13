<?php

use App\Http\Controllers\Api\ApiFunctionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('api_customer_login', [ApiFunctionController::class, 'api_customer_login'])->name('api_customer_login');
 
// $id = Auth::guard('c_user')->user()->user_name;
// $intoken = date("ymd") . '' . $id . '' . date("H");
// $token = hash('SHA512', $intoken);
