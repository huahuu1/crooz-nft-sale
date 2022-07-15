<?php

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\TransactionController;
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

Route::controller(AuthController::class)->group(function() {
    Route::post('register', 'register');
    Route::post('register-wallet/{wallet_address}', 'registerByWalletAddress');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group( function () {
    Route::controller(UserController::class)->group(function(){
        Route::get('users', 'index');
        Route::put('update-email/{id}', 'updateEmailByWalletAddress');
    });

    Route::get('logout', [AuthController::class, 'logout']);

    Route::controller(TransactionController::class)->group(function() {
        Route::post('send-transaction/{wallet_address}/{tx_hash}', 'createDepositTransaction');
    });
});