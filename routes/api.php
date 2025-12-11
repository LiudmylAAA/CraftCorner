<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('posts', [PostController::class, 'index']);
Route::post('posts', [PostController::class, 'store']);
Route::put('posts/{id}', [PostController::class, 'update']);   
Route::delete('posts/{id}', [PostController::class, 'destroy']);

Route::post('/users/{id}/avatar', [AuthController::class, 'updateAvatar']);

Route::get('posts/{id}', [PostController::class, 'show']);
Route::post('posts/{id}/like', [PostController::class, 'toggleLike']);
Route::post('posts/{id}/comments', [PostController::class, 'addComment']);
Route::delete('comments/{id}', [PostController::class, 'deleteComment']);
