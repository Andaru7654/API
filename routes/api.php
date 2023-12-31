<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\AuthenticationController;

Route::get('/es', function () {
    return response()->json([
        'message' => 'hai'
    ]);
});
// login,logout,&me with sanctum
Route::post('login', [AuthenticationController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthenticationController::class, 'logout']);
    Route::get('me',[AuthenticationController::class ,'me']);
    Route::post('posts', [PostController::class, 'store']);
    Route::patch('posts/{id}', [PostController::class, 'update'])->middleware('pemilik-postingan');
    Route::delete('posts/{id}', [PostController::class, 'destroy'])->middleware('pemilik-postingan');

    Route::get('comment',[CommentController::class,'store']);
    Route::patch('comment/{id}',[CommentController::class,'update'])->middleware('pemilik-komentar');
    Route::delete('comment/{id}',[CommentController::class,'destroy'])->middleware('pemilik-komentar');
});

Route::get('posts', [PostController::class, 'index']);
Route::get('post/{id}', [PostController::class, 'show']);
Route::get('post2/{id}', [PostController::class, 'show2']);

