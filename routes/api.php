<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// controllers
use App\Http\Controllers\PostsController;
use App\Http\Controllers\CommentsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// NEWS CRUD OPS
Route::post('/news/store', [PostsController::class, 'store']);
Route::get('/post/{id}', [PostsController::class, 'show']);
Route::get('/posts', [PostsController::class, 'show_all']);
Route::match(['post', 'put'],'/post/{id}', [PostsController::class, 'update']);
Route::match(['delete'],'/post/{id}', [PostsController::class, 'destroy']);

// upvote
Route::post('/upvote/{id}',[PostsController::class, 'upvotePost']);

// COMMENTS CRUD OPS
Route::post('/comment/store', [CommentsController::class, 'store']);
Route::get('/comment/{id}', [CommentsController::class, 'show']);
Route::get('/comments', [CommentsController::class, 'show_all']);
Route::match(['post', 'put'],'/comment/update', [CommentsController::class, 'update']);
Route::match(['delete'],'/comment/delete/{id}', [CommentsController::class, 'destroy']);
