<?php

use App\Http\Controllers\TestController;
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

Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::get('/home', [App\Http\Controllers\web\HomeController::class, 'index'])->name('home');
Route::get('/showExams', [App\Http\Controllers\web\ExamController::class, 'showExams'])->name('showExams');


// Path
Route::get('praticalresults', [PraticalResultsController::class, 'showUpload'])->name('praticalresults');

Route::post('praticalresults/import', [PraticalResultsController::class, 'uploadResults'])->name('praticalresults.import');
