<?php

use App\Http\Controllers\v1\CategoryController;
use App\Http\Controllers\v1\CommentController;
use App\Http\Controllers\v1\IdeaController;
use App\Http\Controllers\v1\VoteController;
use Illuminate\Support\Facades\Route;

Route::apiResource('ideas', IdeaController::class)->only('index', 'show');
Route::apiResource('comments', CommentController::class)->only('index');
Route::apiResource('categories', CategoryController::class)->only('index');

Route::middleware('auth:api')->group(function () {
    Route::apiResource('ideas', IdeaController::class)->except('index', 'show');
    Route::post('ideas/mark-as-spam/{idea}', [IdeaController::class, 'markAsSpam']);
    Route::post('ideas/not-spam/{idea}', [IdeaController::class, 'notSpam']);
    Route::post('ideas/update-status/{idea}', [IdeaController::class, 'updateStatus']);

    Route::apiResource('votes', VoteController::class)->only('index', 'store');

    Route::apiResource('comments', CommentController::class)->only('store', 'destroy');
    Route::post('comments/mark-as-spam/{comment}', [CommentController::class, 'markAsSpam']);
    Route::post('comments/not-spam/{comment}', [CommentController::class, 'notSpam']);
});
