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
            Route::group([
                'prefix' => 'import',
            ], function () {
                //import private user unlock balance excel
                Route::post('private-user/unlock-balance', [TransactionController::class, 'importPrivateUserUnlockBalance']);
            });

            Route::controller(PrivateUnlockController::class)->group(function () {
                Route::group([
                    'prefix' => 'private-unlock',
                ], function () {
                    //change status when release date is up to date
                    Route::get('check-status', 'checkStatusUserWithdrawalRequest');
                });
            });

            // logout
            Route::get('logout', [AuthAdminController::class, 'logout']);
        });
    });
});
