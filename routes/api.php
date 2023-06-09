<?php

use App\Http\Controllers\Api\InformationController;
use App\Http\Controllers\Api\MyPageController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\AuthController;
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

Route::middleware(['language'])->group(function () {
    //Router authorization
    Route::controller(AuthController::class)->group(function () {
        Route::post('register', 'register');
        // Route::post('register-wallet', 'registerByWalletAddress');
        Route::post('login', 'login');

        // create signature
        Route::post('register-signature', 'registerSignature');
        // login with signature
        Route::post('login-web3', 'loginWeb3');

        Route::group([
            'prefix' => 'authentication',
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
    Route::middleware('auth:sanctum')->group(function () {
        //user routes
        Route::controller(UserController::class)->group(function () {
            Route::get('users', 'index');
            Route::put('update-email', 'updateEmailByWalletAddress');
            Route::post('create-user-balance', 'createDefaultBalanceByWalletAddress');
        });

        //logout route
        Route::get('logout', [AuthController::class, 'logout']);

        //transaction routes
        Route::controller(TransactionController::class)->group(function () {
            // send transaction
            Route::post('buy-token-transaction', 'createDepositTokenTransaction');
            Route::post('buy-nft-transaction', 'createDepositNftTransaction');
            Route::post('insert-missed-transaction', 'insertMissedTransaction');
            // register payment with credit card
            Route::post('register-payment/credit', 'registerPaymentWithCreditCard');
            // complete payment with credit card
            Route::put('payment/credit', 'paymentWithCreditCard');
            // get history purchase nft auction list
            Route::get('package-history-nft-auction', 'historyNftAuctionByPackage');
            // complete payment with coupon
            Route::post('payment/coupon', 'paymentWithCoupon');
            Route::post('payment/coupon-5th-sale/credit', 'paymentWithCoupon5thSaleByCredit');
            Route::post('payment/coupon-5th-sale/crypto', 'paymentWithCoupon5thSaleByCrypto');
            Route::get('check-user-with-discount-coupon', [TransactionController::class, 'checkUserWithDiscountCoupon']);
        });

        //my page routes
        Route::controller(MyPageController::class)->group(function () {
            Route::group([
                'prefix' => 'my-page',
            ], function () {
                //get history purchase list in my page
                Route::get('history-list/{user}/{max_per_page?}', 'getHistoryListOfUser');
                //get balances of a user
                Route::get('balance/{user}', 'getBalanceOfUser');
                //get nfts of a user
                Route::get('nft/{user}/{nft_type}/{max_per_page?}', 'getNftOfUserByTypeId');
                //user requests to withdrawal token
                Route::post('withdraw-token', 'requestToWithdrawToken');
                //update status of user_withdrawals
                Route::put('withdraw-token/update-status', 'updateStatusWithdrawRequest');
                // count nfts group by type id
                Route::get('count-nft-type/{user}', 'countNftGroupByTypeId');
                // get user profile
                Route::get('user/profile/{user}', 'getUserProfile');
            });
        });

        Route::controller(UserController::class)->group(function () {
            Route::group([
                'prefix' => 'my-page',
            ], function () {
                //Send email reset password to the user
                Route::post('request-reset-password', 'sendEmailResetPassword');
                //Reset the password of user
                Route::post('reset-password/{token}', 'resetPassword');
                //Change the password of user
                Route::post('change-password/{user}', 'changePassword');
            });
        });

        //ticket routes
        Route::controller(TicketController::class)->group(function () {
            //get user's tickets number
            Route::get('tickets-number/{user}/{auction_id}', 'getTicketsNumber');
            //use gacha ticket
            Route::post('gacha-ticket', 'useGachaTicket');
        });
    });

    //display nft auction info
    Route::get('nft-auction', [InformationController::class, 'getLatestInfoNftAuction']);
    Route::get('all-nft-auction', [InformationController::class, 'getAllInfoNftAuction']);
    Route::get('nft-auction/{id}', [InformationController::class, 'getInfoNftAuctionById']);
    //get exchange rate
    Route::get('exchange-rate/{symbol}', [InformationController::class, 'getExchangeRateBySymbol']);
    Route::get('exchange-rate', [InformationController::class, 'getLastExchangeRate']);
    // get transactions ranking
    Route::get('get-auction-ranking', [TransactionController::class, 'getTransactionsRanking']);

    //purchase list
    Route::group([
        'prefix' => 'purchase-list',
    ], function () {
        //purchase list of nft auction
        Route::get('nft-auction/{user}/{max_per_page?}', [
            TransactionController::class, 'getPurchaseListOfNftAuctionOfUser'
        ]);
    });

    Route::post('gacha', [
        TransactionController::class, 'gachaTicketApi'
    ]);
});
// health check
Route::get('health_check', static function () {
    $status = ['status' => 200, 'message' => 'success'];
    return compact('status');
});


require __DIR__ . '/admin.php';
