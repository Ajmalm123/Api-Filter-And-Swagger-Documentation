<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('posts', PostController::class);
Route::patch('post-change-status/{post}', [PostController::class, 'changeStatus']);

Route::apiResource('users', UserController::class);
Route::patch('user-change-status/{post}', [UserController::class, 'changeStatus']);
