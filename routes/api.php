<?php
namespace App\Http\Controllers\API;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// api/register || login || logout
Route::post('register',[AuthController::class, 'register'])->name('user.register');
Route::post('login', [AuthController::class, 'login'])->name('user.login');
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:api');

// routes for user to verify email
Route::get('user', [AuthController::class, 'user'])->middleware('auth:api');
Route::post('forgot', [ForgotResetController::class,'forgot']);
Route::post('reset', [ForgotResetController::class,'reset']);

//routes for MessageController
Route::middleware('auth:api')->group( function (){
    Route::get('messages', [MessageController::class, 'messages']);
    Route::get('getMessageById/{id}', [MessageController::class, 'getMessageById']);
    Route::put('markMessageAsRead/{id}', [MessageController::class, 'markMessageAsRead']);
    Route::delete('deleteMessage/{id}', [MessageController::class, 'deleteMessage']);
});

//routes for NotificationController
Route::middleware('auth:api')->group( function (){
    Route::get('notifications', [NotificationController::class, 'notifications']);
    Route::get('getNotificationById/{id}', [NotificationController::class, 'getNotifiationById']);
    //Route::put('close-notification', [NotificationController::class, 'closeNotifications']);
    //Route::put('open-notification', [NotificationController::class, 'openNotifications']);
    Route::put('mark-read/{id}', [NotificationController::class, 'markNotificationAsRead']);
    Route::delete('deleteNotification/{id}', [NotificationController::class, 'deleteNotification']);
    Route::delete('clearNotifications', [NotificationController::class, 'clearNotifications']);
});