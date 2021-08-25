<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\MessageController;
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

// api/register || login || logout
Route::post('register', [AuthController::class, 'register'])->name('user.register');
Route::post('login', [AuthController::class, 'login'])->name('user.login');
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:api');

// routes for user to verify email
Route::get('user', [AuthController::class, 'user'])->middleware('auth:api');
Route::post('forgot', [ForgotResetController::class, 'forgot']);
Route::post('reset', [ForgotResetController::class, 'reset']);


// Routes For User
Route::middleware('auth:api')->group(function () {
    // Routes For MessageController
    Route::get('messages', [MessageController::class, 'messages']);
    Route::get('getMessageById/{id}', [MessageController::class, 'getMessageById']);
    Route::put('mark-read/{id}', [MessageController::class, 'markMessageAsRead']);
    Route::delete('deleteMessage/{id}', [MessageController::class, 'deleteMessage']);

    // Current Course For User
    Route::get('/course', [CourseController::class, 'index']);

    // Routes For Path (Show All, Store A Path, Show Current A Path)
    Route::get('/paths', [PathController::class, 'index']);
    Route::post('/paths/store', [PathController::class, 'store']);
    Route::get('/paths/show', [PathController::class, 'show']);
});
