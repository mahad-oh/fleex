<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\AuthController;


Route::post('/auth/signup', [AuthController::class, 'signup']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    Route::get('/vouchers/redeem', [VoucherController::class, 'redeem']);
    Route::get('/vouchers/check', [VoucherController::class, 'check']);
    Route::get('/vouchers/activate', [VoucherController::class, 'activate'])->name('voucher.activer');
});


