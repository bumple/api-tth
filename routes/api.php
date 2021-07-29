<?php


use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\WalletController;
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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::post('test_login',[AuthController::class,'login']);
Route::post('test_register',[AuthController::class,'register']);
Route::post('login',[AuthUserController::class,'login']);
Route::post('register',[AuthUserController::class,'register']);


Route::resource('wallet',WalletController::class);
Route::post('wallet/addmoney/:id',[WalletController::class,'plusMoney']);
