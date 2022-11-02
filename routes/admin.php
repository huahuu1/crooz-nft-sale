<?php

use App\Http\Controllers\Api\NftController;
use App\Http\Controllers\Api\PrivateUnlockController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Auth\AuthAdminController;
use Illuminate\Support\Facades\Route;

Route::middleware(['language'])->group(function () {
    Route::prefix('admin')->group(function () {
        // Must not login route
        Route::post('login', [AuthAdminController::class, 'login']);

        //Must admin login route
        Route::middleware(['auth:sanctum', 'auth.admin'])->group(function () {
            // import nft
            Route::post('import-nft', [NftController::class, 'importNft']);
            Route::get('export-nft', [NftController::class, 'exportNft']);
            //get auction nft data
            Route::get('get-auction-nft', [NftController::class, 'getAuctionNftData']);
            Route::group([
                'prefix' => 'import',
            ], function () {
                //import private user unlock balance excel
                Route::post('private-user/unlock-balance', [TransactionController::class, 'importPrivateUserUnlockBalance']);
            });

            Route::controller(PrivateUnlockController::class)->group(function () {
                Route::get('private-unlock-balance', 'getPrivateUserUnlockBalances');
                Route::get('user-withdrawal', 'getUserWithdrawalsHasPrivateUnlock');
                Route::group([
                    'prefix' => 'private-unlock',
                ], function () {
                    //change status when release date is up to date
                    Route::get('get-data/{max_per_page?}', 'getDataPrivateUnlock');
                    //change status when release date is up to date
                    Route::get('check-unlock-date', 'checkUnlockTokenUpToDate');
                    //transfer token when approve user withdrawal request
                    Route::post('approve', 'approveWithdrawalRequest');
                    //check status of withdrawal request
                    Route::post('check-status', 'checkStatusWithdrawalRequests');
                });
            });

            // logout
            Route::get('logout', [AuthAdminController::class, 'logout']);
        });
    });
});
