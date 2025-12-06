<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

//Public route
//Auth routes (login/register)
Route::get('/login', function() {return view('auth.login'); })->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', function() {return view('auth.register'); })->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

//Protected route
Route::middleware('auth')->group(function(){
    //Homepage
    Route::get('/', [TaskController::class, 'index'])->name('dashboard');

    //Task Actions
    Route::post('/tasks', [TaskController::class, 'store'])->name('task.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('task.show');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('task.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('task.destroy');

    //Category Acctions
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    //Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
