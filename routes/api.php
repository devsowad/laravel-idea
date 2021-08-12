<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\IdeaController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

Route::apiResource('ideas', IdeaController::class)->only('index', 'show');
Route::apiResource('categories', CategoryController::class)->only('index');

Route::middleware('auth:api')->group(function () {
    Route::apiResource('ideas', IdeaController::class)->except('index', 'show');
    Route::post('ideas/mark-as-spam', [IdeaController::class, 'markAsSpam']);
    Route::post('ideas/not-spam', [IdeaController::class, 'notSpam']);
    Route::post('ideas/update-status/{idea}', [IdeaController::class, 'updateStatus']);

    Route::apiResource('votes', VoteController::class)->only('index', 'store');

    Route::apiResource('comments', CommentController::class)->only('store', 'destroy');
    Route::post('comments/mark-as-spam', [CommentController::class, 'markAsSpam']);
    Route::post('comments/not-spam', [CommentController::class, 'notSpam']);
});

Route::prefix('auth')->group(function () {
    Route::middleware('guest:api')->group(function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
    });
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('user', [AuthController::class, 'me']);
    });
});
