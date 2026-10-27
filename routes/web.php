<?php

use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'auth.login')->name('login');

Route::get('/posts', [BlogController::class, 'index'])->name('blog.index');
Route::get('/posts/{post:slug}', [BlogController::class, 'show'])->name('blog.show');
Route::view('/dashboard', 'admin.Dashboard')->name('dashboard');
