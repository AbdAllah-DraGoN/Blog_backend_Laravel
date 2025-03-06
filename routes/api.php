<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::get('/posts', function (Request $request) {
//     return $request->user();
// });

// Users
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
// Route::get('/users/profile', [UserController::class, 'showCurrentUser'])->middleware('auth:sanctum');
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::delete(
    '/logout', [UserController::class, 'logout']
)->middleware('auth:sanctum');
Route::delete(
    '/user', [UserController::class, 'destroy']
)->middleware('auth:sanctum');

// Posts
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/ordered', [PostController::class, 'orderPostsByCategory']);
Route::get('/posts/{id}', [PostController::class, 'show']);
Route::get('/user/posts', [PostController::class, 'userPosts'])->middleware('auth:sanctum');
Route::post('/posts', [PostController::class, 'store'])->middleware('auth:sanctum');
Route::put('/posts/{id}', [PostController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/posts/{id}', [PostController::class, 'destroy'])->middleware('auth:sanctum');

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::post('/categories', [CategoryController::class, 'store'])->middleware('auth:sanctum');
Route::put('/categories/{id}', [CategoryController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/categories/{id}', [CategoryController::class, 'delete'])->middleware('auth:sanctum');

// Comments
Route::get('/posts/{id}/comments', [CommentController::class, 'index']);
Route::post('/posts/{id}/comments', [CommentController::class, 'store'])->middleware('auth:sanctum');
Route::put('/comments/{id}', [CommentController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/comments/{id}', [CommentController::class, 'destroy'])->middleware('auth:sanctum');

// Favorite Posts
Route::get('/posts/favorites/all', [PostController::class, 'getUserFavorites'])->middleware('auth:sanctum');
Route::post('/posts/{id}/favorite', [PostController::class, 'addToFavorites'])->middleware('auth:sanctum');
Route::delete('/posts/{id}/favorite', [PostController::class, 'deleteFromFavorites'])->middleware('auth:sanctum');



