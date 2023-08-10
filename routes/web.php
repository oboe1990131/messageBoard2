<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageBoardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MembercenterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/toLogin', [LoginController::class, 'toLogin']);
Route::get('/regist', [MembercenterController::class, 'create']);
Route::post('/registtodb', [MembercenterController::class, 'store']);

Route::group(['middleware'=>['CustomAuth'], 'namespace' => '\\'], function(){
    Route::resource('/messageboard', MessageBoardController::class);
    Route::resource('/membercenter', MembercenterController::class);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');