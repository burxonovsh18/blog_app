<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('posts', [PostController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('my/posts', [PostController::class, 'mine']);
    Route::post('posts', [PostController::class, 'store']);
    Route::put('posts/{post}', [PostController::class, 'update'])->whereNumber('post');
    Route::delete('posts/{post}', [PostController::class, 'destroy'])->whereNumber('post');
});

// Public single post by slug (declared after the numeric routes above)
Route::get('posts/{post:slug}', [PostController::class, 'show']);
