<?php

use App\Http\Controllers\WEB\PathController;
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


// Routes Access Just From Admin
Route::group(['middleware' => ['admin', 'auth']], function () {

    // Routes Paths
    Route::get('/paths', [PathController::class, 'index'])->name('paths.index');
    Route::get('/paths/trashed', [PathController::class, 'trashed'])->name('paths.trashed');
    Route::get('/paths/create', [PathController::class, 'create'])->name('path.create');
    Route::post('/paths/store', [PathController::class, 'store'])->name('path.store');
    Route::get('/paths/{id}/currentusers/', [PathController::class, 'currentUsersPath'])->name('paths.currentusers');
    Route::get('/paths/{id}/users', [PathController::class, 'allUsersPath'])->name('paths.users');
    Route::get('/paths/{id}/currentexcludeuser', [PathController::class, 'currentExcludeUser'])->name('paths.currentexcludeuser');
    Route::get('/paths/{id}/excludeusers', [PathController::class, 'allExcludeUser'])->name('paths.excludeusers');
    Route::get('/paths/{id}/destroy', [PathController::class, 'destroy'])->name('paths.destroy');
    Route::get('/paths/{id}/restore', [PathController::class, 'restore'])->name('paths.restore');

    // Home Page
    Route::get('/home', [App\Http\Controllers\web\HomeController::class, 'index'])->name('home');
    Route::get('/showExams', [App\Http\Controllers\web\ExamController::class, 'showExams'])->name('showExams');
});

Route::get('/', function () {
    auth()->logout();
    return view('auth.login');
});

Auth::routes();

// Login page
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
