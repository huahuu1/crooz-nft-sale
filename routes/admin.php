<?php

use App\Http\Controllers\Auth\AuthAdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    // Must not login route
    Route::controller(AuthAdminController::class)->group(function () {
        Route::post('login', 'login');
    });

    //Must admin login route
    Route::middleware(['auth.admin'])->group(function () {
    });
});
