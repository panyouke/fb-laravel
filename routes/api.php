<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::prefix('auth')->middleware(['crosstttp'])->group(function () {
    // 不需要登录
    Route::post('login', [AuthController::class, 'login']);

    // 需要登录
    Route::middleware(['api_auth'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('uploadimg', [AuthController::class, 'uploadimg']);
    });
});
