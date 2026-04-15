<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');
use App\Http\Controllers\Auth\FacebookAuthController;

Route::get('/auth/facebook/redirect', [FacebookAuthController::class, 'redirect'])
    ->name('facebook.redirect');

Route::get('https://test.malapan.online/auth/facebook/callback', [FacebookAuthController::class, 'callback'])
    ->name('facebook.callback');
