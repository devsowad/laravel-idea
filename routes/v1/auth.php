<?php

use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\NotificationController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:api')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::post('refresh', [AuthController::class, 'refresh']);

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'me']);

    Route::get('user/notifications', [NotificationController::class, 'all']);
    Route::post('user/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
});
