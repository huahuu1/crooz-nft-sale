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

//Router authorization
Route::controller(AuthController::class)->group(function() {
    Route::post('register', 'register');
    Route::post('register-wallet', 'registerByWalletAddress');
    Route::post('login', 'login');

    Route::group([
        'prefix' => 'authentication'
    ], function () {
        //Send email include token to the user
        Route::post('send_token', 'sendToken');
        //confirm the token is correct or not
        Route::post('confirm_token', 'confirmToken');
        //check email is existed by wallet address
        Route::get('check-email/{wallet_address}', [UserController::class, 'checkEmailUserByWalletAddress']);
    });
});

//Must login routes
Route::middleware('auth:sanctum')->group( function () {
    //user routes
    Route::controller(UserController::class)->group(function(){
        Route::get('users', 'index');
        Route::put('update-email', 'updateEmailByWalletAddress');
    });

    //logout route
    Route::get('logout', [AuthController::class, 'logout']);

    //transaction routes
    Route::controller(TransactionController::class)->group(function() {
        Route::post('buy-token-transaction', 'createDepositTokenTransaction');
        Route::post('buy-nft-transaction', 'createDepositNftTransaction');
    });
});


