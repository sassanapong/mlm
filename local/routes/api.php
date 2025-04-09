<?php

use App\Http\Controllers\Api\ApiFunctionController;
use App\Http\Controllers\Api\ApiFunction2Controller;
use App\Http\Controllers\Api\ApiFunction3Controller;
use App\Http\Controllers\Api\ApiFunction4Controller;
use App\Http\Controllers\Api\ApiFunction5Controller;
use App\Http\Controllers\Api\ApiFunction7Controller;
use App\Http\Controllers\Api\RunCodeController;
use App\Http\Controllers\Api\RegisterUrlController;

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

use App\Http\Controllers\Frontend\FC\LineController;

Route::post('/LineWebhook', [LineController::class, 'LineWebhook']);

Route::get('dataset_qualification', [ApiFunctionController::class, 'dataset_qualification'])->name('dataset_qualification');
Route::post('api_customer_login', [ApiFunctionController::class, 'api_customer_login'])->name('api_customer_login');
Route::get('dataset_changwat', [ApiFunctionController::class, 'dataset_changwat'])->name('dataset_changwat');
Route::get('dataset_amphuress', [ApiFunctionController::class, 'dataset_amphuress'])->name('dataset_amphuress');
Route::get('dataset_tambon', [ApiFunctionController::class, 'dataset_tambon'])->name('dataset_tambon');
Route::post('storeRegister', [ApiFunction2Controller::class, 'storeRegister'])->name('storeRegister');

Route::get('dataset_categories', [ApiFunction5Controller::class, 'dataset_categories'])->name('dataset_categories');
Route::get('productList', [ApiFunction5Controller::class, 'productList'])->name('productList');
Route::get('productDetail', [ApiFunction5Controller::class, 'productDetail'])->name('productDetail');
Route::get('producSearch', [ApiFunction5Controller::class, 'producSearch'])->name('producSearch');

Route::middleware(['auth.jwt'])->group(function () {
    Route::post('changePassword', [ApiFunction3Controller::class, 'changePassword'])->name('changePassword');
    Route::post('getUserProfile', [ApiFunction3Controller::class, 'getUserProfile'])->name('getUserProfile');
    Route::get('getUserProfile_token', [ApiFunction3Controller::class, 'getUserProfile_token'])->name('getUserProfile_token');
    Route::post('updateProfile', [ApiFunction3Controller::class, 'updateProfile'])->name('updateProfile');
    Route::post('deposit', [ApiFunction3Controller::class, 'deposit'])->name('deposit');
    Route::post('withdraw', [ApiFunction3Controller::class, 'withdraw'])->name('withdraw');
    Route::post('get_sponser', [ApiFunction4Controller::class, 'get_sponser'])->name('get_sponser');
});
Route::post('payment_submit', [ApiFunction7Controller::class, 'payment_submit'])->name('payment_submit');

Route::get('db_code_pv', [RunCodeController::class, 'db_code_pv'])->name('db_code_pv');
Route::get('db_code_bonus/{type}', [RunCodeController::class, 'db_code_bonus'])->name('db_code_bonus');

Route::post('RegisterUrl', [RegisterUrlController::class, 'store_register'])->name('RegisterUrl');

Route::post('payment_complete_backend', 'Frontend\FC2024\ApiPAymentController@payment_complete_backend')->name('payment_complete_backend');
// $id = Auth::guard('c_user')->user()->user_name;
// $intoken = date("ymd") . '' . $id . '' . date("H");
// $token = hash('SHA512', $intoken);
