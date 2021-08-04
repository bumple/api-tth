<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
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
Route::post('login', [AuthUserController::class, 'login'])->name('login');


Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::resource('wallet',WalletController::class);
    Route::put('wallet/info/{id}',[WalletController::class,'plusMoney'])->name('wallet.pushMoney');
    Route::resource('transaction', TransactionController::class);
    Route::resource('category', CategoryController::class);
    Route::get('transaction/info/{id}',[TransactionController::class,'findByCategoryId']);
    Route::get('category/info/{id}',[CategoryController::class,'getCategoryByWalletId']);
    Route::get('statistics/{id}',[CategoryController::class,'categoryStatistic']);
    Route::get('report/transactions',[TransactionController::class,'getReportTransaction']);
    Route::post('report',[TransactionController::class,'getReportFromToDate']);

    Route::post('/get-wallet-by-userid/{id}',[WalletController::class,'getWalletsByUserid']);
    Route::post('/register', [AuthUserController::class, 'register'])->name('register');
    Route::post('/logout', [AuthUserController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthUserController::class, 'refresh'])->name('refresh');
    Route::get('/user-profile', [AuthUserController::class, 'userProfile']);
});
