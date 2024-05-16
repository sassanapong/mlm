<?php

use App\Http\Controllers\Api\ApiFunctionController;
use App\Http\Controllers\Api\ApiFunction2Controller;
use App\Http\Controllers\Api\ApiFunction3Controller;

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
Route::get('dataset_changwat', [ApiFunctionController::class, 'dataset_changwat'])->name('dataset_changwat');
Route::get('dataset_amphuress', [ApiFunctionController::class, 'dataset_amphuress'])->name('dataset_amphuress');
Route::get('dataset_tambon', [ApiFunctionController::class, 'dataset_tambon'])->name('dataset_tambon');
Route::post('storeRegister', [ApiFunction2Controller::class, 'storeRegister'])->name('storeRegister');
Route::middleware(['auth.jwt'])->group(function () {
    Route::post('getUserProfile', [ApiFunction3Controller::class, 'getUserProfile'])->name('getUserProfile');
    Route::post('deposit', [ApiFunction3Controller::class, 'deposit'])->name('deposit');
    Route::post('withdraw', [ApiFunction3Controller::class, 'withdraw'])->name('withdraw');
});


// $id = Auth::guard('c_user')->user()->user_name;
// $intoken = date("ymd") . '' . $id . '' . date("H");
// $token = hash('SHA512', $intoken);
