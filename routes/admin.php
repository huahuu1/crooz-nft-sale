<?php

use App\Http\Controllers\Api\NftController;
use App\Http\Controllers\Auth\AuthAdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    // Must not login route
    Route::post('login', [AuthAdminController::class, 'login']);

    //Must admin login route
    Route::middleware(['auth.admin'])->group(function () {
        // import nft
        Route::post('import-nft', [NftController::class, 'importNft']);
        Route::get('export-nft', [NftController::class, 'exportNft']);

        // logout
        Route::get('logout', [AuthAdminController::class, 'logout']);
    });
});
