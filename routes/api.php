<?php

use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Public route login to get a token
Route::post('/login', [ApiAuthController::class, 'login']);

//Protected routes require valid bearer token
Route::middleware('auth:sanctum')->group(function(){
    // User Profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Logout
    Route::post('/logout', [ApiAuthController::class, 'logout']);

    // --- TASKS API ---
    Route::get('/tasks', [TaskController::class, 'index']); // Read
    Route::post('/tasks', [TaskController::class, 'store']); // Create
    Route::get('/tasks/{task}', [TaskController::class, 'show']); // Read Single
    Route::put('/tasks/{task}', [TaskController::class, 'update']); // Update
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']); // Delete

    // --- CATEGORIES API ---
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
});
