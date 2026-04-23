<?php

use App\Http\Controllers\FbBusinessController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\FacebookAuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/auth/facebook/redirect', [FacebookAuthController::class, 'redirect'])
    ->name('facebook.redirect');

Route::get('/auth/facebook/callback', [FacebookAuthController::class, 'callback'])
    ->name('facebook.callback');

// 1. 显示发帖表单的页面
Route::get('/facebook/page/publish', [FbBusinessController::class, 'showForm'])->name('facebook.page.show');

// 2. 接收表单提交并处理发帖
Route::post('/facebook/page/publish', [FbBusinessController::class, 'sendPost'])->name('facebook.page.publish');

Route::get('/invite', [FbBusinessController::class, 'showInviteForm'])->name('facebook.page.showInviteForm');

Route::post('/invite', [FbBusinessController::class, 'processInvite'])->name('facebook.page.processInvite');

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
