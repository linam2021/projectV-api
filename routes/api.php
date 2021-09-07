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

//Routes for register, verifyEmail, resendVerificationEmailCode, login and logout
Route::post('register', [AuthController::class, 'register']);
Route::post('verifyEmail', [AuthController::class, 'verifyEmail']);
Route::post('resendVerificationEmailCode', [AuthController::class, 'resendVerificationEmailCode']);
Route::post('login', [AuthController::class, 'login']);
Route::get('user', [AuthController::class, 'user'])->middleware('auth:api');
Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:api');
 
// Routs for forgotPassword and passwordReset
Route::post('forgotPassword', [ForgotResetController::class, 'forgotPassword']);
Route::post('passwordReset', [ForgotResetController::class, 'passwordReset']);

// Routes For User
Route::middleware('auth:api')->group(function () {
    // Routes For MessageController
    Route::get('messages', [MessageController::class, 'messages']);
    Route::get('getMessageById/{id}', [MessageController::class, 'getMessageById']);
    Route::put('markMessageAsRead/{id}', [MessageController::class, 'markMessageAsRead']);
    Route::delete('deleteMessage/{id}', [MessageController::class, 'deleteMessage']);

    // Current Course For User
    Route::get('currnetUserCourse', [CourseController::class, 'currnetUserCourse']);

    // Routes For Path (Show All, Store A Path, Show Current A Path)
    Route::get('paths', [PathController::class, 'index']);
    Route::post('userpath/store', [UserPathController::class, 'store']);
    Route::get('userpath/show', [UserPathController::class, 'show']);
});

//routes for NotificationController
Route::middleware('auth:api')->group(function () {
    Route::get('notifications', [NotificationController::class, 'notifications']);
    Route::get('getNotificationById/{id}', [NotificationController::class, 'getNotifiationById']);
    //Route::put('close-notification', [NotificationController::class, 'closeNotifications']);
    //Route::put('open-notification', [NotificationController::class, 'openNotifications']);
    Route::put('mark-read/{id}', [NotificationController::class, 'markNotificationAsRead']);
    Route::delete('deleteNotification/{id}', [NotificationController::class, 'deleteNotification']);
    Route::delete('clearNotifications', [NotificationController::class, 'clearNotifications']);
});

//routes for ProfilesController
Route::post('addProfile',[ProfilesController::class ,'addProfile'])->middleware('auth:api');
Route::get('showProfile',[ProfilesController::class ,'showProfile'])->middleware('auth:api');

//routes for EventsController
Route::get('showAllEvents',[EventsController::class ,'showAllEvents'])->middleware('auth:api');

Route::post('getAllQuestionBankPaths',[QuestionBankController::class ,'getAllQuestionBankPaths'])->middleware('auth:api');
Route::post('showQuestionBankPathCourses',[QuestionBankController::class ,'showQuestionBankPathCourses'])->middleware('auth:api');
Route::post('getExamQuestions',[QuestionBankController::class ,'getExamQuestions'])->middleware('auth:api');
