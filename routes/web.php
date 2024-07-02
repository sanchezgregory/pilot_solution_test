<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function(){
    Route::get('users',[UserController::class, 'index'])->name('users.index')->middleware('admin');
});
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
