<?php

use App\http\Controllers\Auth\LoginController;
use App\Http\Controllers\WEB\AdminController;
use App\Http\Controllers\WEB\HomeController;
use App\Http\Controllers\WEB\PathController;
use App\Http\Controllers\WEB\ExamController;
use App\Http\Controllers\WEB\CourseController;
use App\Http\Controllers\WEB\PraticalResultsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    auth()->logout();
    return view('auth.login');
});

Auth::routes();

// Login Route
Route::post('/login', [LoginController::class, 'login'])->name('login');

// Routes Access Just From Admin
Route::group(['middleware' => ['admin', 'auth']], function () {

    // Home Route
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Paths Routes
    Route::get('/allpaths', [PathController::class, 'allwithTrashed'])->name('paths.allwithTrashed');
    Route::get('/paths/create', [PathController::class, 'create'])->name('path.create');
    Route::post('/paths/store', [PathController::class, 'store'])->name('path.store');
    Route::get('/regPath/{id}', [PathController::class, 'startRegisterInPath'])->name('path.startRegisterInPath');
    Route::post('/paths/setStartReg/{id}', [PathController::class, 'setStartRegisterPath'])->name('path.setStartRegisterPath');
    Route::get('/paths/finishReg/{id}', [PathController::class, 'finishRegister'])->name('path.finishRegister');
    Route::get('/paths/startPath/{id}', [PathController::class, 'startPath'])->name('path.startPath');
    Route::get('/paths/{id}/destroy', [PathController::class, 'destroy'])->name('paths.destroy');

    //Courses Routes
    Route::get('/courses/{id}', [CourseController::class, 'index'])->name('course.index');
    Route::get('/courses/create/{id}', [CourseController::class, 'create'])->name('course.create');
    Route::post('/courses/store/{id}/{qbid}', [CourseController::class, 'store'])->name('course.store');



    Route::get('/paths', [PathController::class, 'index'])->name('paths.index');
    Route::get('/paths/trashed', [PathController::class, 'trashed'])->name('paths.trashed');
    Route::get('/paths/{id}/currentusers/', [PathController::class, 'currentUsersPath'])->name('paths.currentusers');
    Route::get('/paths/{id}/users', [PathController::class, 'allUsersPath'])->name('paths.users');
    Route::get('/paths/{id}/currentexcludeuser', [PathController::class, 'currentExcludeUser'])->name('paths.currentexcludeuser');
    Route::get('/paths/{id}/excludeusers', [PathController::class, 'allExcludeUser'])->name('paths.excludeusers');
    Route::get('/paths/{id}/restore', [PathController::class, 'restore'])->name('paths.restore');

    // Search User
    Route::get('/users/search/{info}', [AdminController::class, 'searchUsers'])->name('users.search');

    // Change Permision
    Route::get('/user/permision/{id}', [AdminController::class, 'permisionAdmin'])->name('admin.permision');

    // Exam Routes
    Route::get('/showExams', [ExamController::class, 'showExams'])->name('showExams');
});

// Exams Pratical Results
Route::get('exams/pratical', [PraticalResultsController::class, 'showUpload'])->name('praticalresults');

Route::post('exams/pratical/import', [PraticalResultsController::class, 'uploadResults'])->name('praticalresults.import');
