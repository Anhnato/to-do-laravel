<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function(){

});

Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
Route::view('/', 'dashboard')->name('dashboard');
