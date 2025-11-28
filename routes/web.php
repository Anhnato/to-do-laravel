<?php

use Illuminate\Support\Facades\Route;

//Homepage
Route::get('/', [TaskController::class, 'index'])->name('dashboard');

//Task Actions
Route::post('/tasks', [TaskController::class, 'store'])->name('task.store');
Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('task.show');
Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('task.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('task.destroy');

//Auth routes (login/register)
Route::get('/login', function() {return view('login'); })->name('login');
Route::get('/register', function() {return view('register'); })->name('register');
