<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MainController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::post('/auth/register',       [AuthController::class, 'createUser']);
Route::post('/auth/login',          [AuthController::class, 'loginUser']);
//Route::apiResource('posts', PostController::class)->middleware('auth:sanctum');

Route::group(['middleware'=>'auth:sanctum'],function (){
    Route::apiResource('posts', PostController::class);
    Route::post('/userSinglePost',           [MainController::class, 'userSinglePost']);
    Route::post('/userPostDelete',           [MainController::class, 'userPostDelete']);
    Route::get('/userPostsArchive',          [MainController::class, 'userPostsArchive']);
    Route::post('/userPostReactive',         [MainController::class, 'userPostReactive']);
    Route::post('/userPostPinned',           [MainController::class, 'userPostPinned']);


});

Route::get('/forceDelete',               [MainController::class, 'forceDelete']);
Route::get('/countUser',                 [MainController::class, 'countUser']);
