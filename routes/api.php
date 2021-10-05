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

//Routes for register, verifyEmail, resendVerificationEmailCode and login 
Route::post('register', [AuthController::class, 'register']);
Route::post('verifyEmail', [AuthController::class, 'verifyEmail']);
Route::post('resendVerificationEmailCode', [AuthController::class, 'resendVerificationEmailCode']);
Route::post('login', [AuthController::class, 'login']);
 
// Routs for forgotPassword and passwordReset
Route::post('forgotPassword', [ForgotResetController::class, 'forgotPassword']);
Route::post('passwordReset', [ForgotResetController::class, 'passwordReset']);

// Routes For User
Route::middleware('auth:api')->group(function () {
    //Routes for userInfo, logout ,addProfile
    Route::get('userInfo', [AuthController::class, 'userInfo']);
    Route::post('addProfile',[AuthController::class ,'addProfile'])->middleware('auth:api');
    Route::get('logout', [AuthController::class, 'logout']);
    
    // Routes For MessageController
    Route::get('messages', [MessageController::class, 'messages']);
    Route::get('getMessageById/{id}', [MessageController::class, 'getMessageById']);
    Route::put('markMessageAsRead/{id}', [MessageController::class, 'markMessageAsRead']);
    Route::delete('deleteMessage/{id}', [MessageController::class, 'deleteMessage']);

    //Routes for NotificationController
    Route::get('notifications', [NotificationController::class, 'notifications']);
    Route::get('getNotificationById/{id}', [NotificationController::class, 'getNotifiationById']);
    //Route::put('close-notification', [NotificationController::class, 'closeNotifications']);
    //Route::put('open-notification', [NotificationController::class, 'openNotifications']);
    Route::put('mark-read/{id}', [NotificationController::class, 'markNotificationAsRead']);
    Route::delete('deleteNotification/{id}', [NotificationController::class, 'deleteNotification']);
    Route::delete('clearNotifications', [NotificationController::class, 'clearNotifications']);

    //Routes for Current Course For User
    Route::get('currnetUserCourse', [CourseController::class, 'currnetUserCourse']);

    // Routes For Path (Show All, Store A Path, Show Current A Path)
    Route::get('paths', [PathController::class, 'index']);
    Route::get('comingpaths', [PathController::class, 'showComingPaths']);
    Route::post('userpath/store', [UserPathController::class, 'store']);
    Route::get('userpath/show', [UserPathController::class, 'show']);
    Route::get('getHeroesCountByType', [UserPathController::class, 'getHeroesCountByType']);
    Route::get('showUserPathLeaderboard', [UserPathController::class, 'showUserPathLeaderboard']);
    Route::post('acceptUser', [UserPathController::class, 'acceptUser']);
    Route::post('rejectUser', [UserPathController::class, 'rejectUser']);


    //Routes for getDateTime and showUserEvents
    Route::get('getDateTime',[UserEventsController::class ,'getDateTime']);
    Route::get('showUserEvents',[UserEventsController::class ,'showUserEvents']);

    //Routes for QuestinBankController
    Route::post('getAllQuestionBankPaths',[QuestionBankController::class ,'getAllQuestionBankPaths']);
    Route::post('getQuestionBankPathCourses',[QuestionBankController::class ,'getQuestionBankPathCourses']);
    Route::post('addExamQuestions',[QuestionBankController::class ,'addExamQuestions']);
    Route::post('getUserExamQuestions',[QuestionBankController::class ,'getUserExamQuestions']);

    //Routes for userExams
    Route::get('examInfo', [UserExamController::class, 'examInfo']);
    Route::post('addUserExamResult', [UserExamController::class, 'addUserExamResult']);
    Route::get('examDate', [UserExamController::class, 'examDate']);
});