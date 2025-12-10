<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('posts', [PostController::class, 'index']);
Route::post('posts', [PostController::class, 'store']);
Route::post('posts/{id}/like/{userId}', [PostController::class, 'like']);
Route::put('posts/{id}', [PostController::class, 'update']);   
Route::delete('posts/{id}', [PostController::class, 'destroy']);
Route::post('/users/{id}/avatar', [AuthController::class, 'updateAvatar']);
