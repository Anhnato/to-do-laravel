<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::middleware('auth')->group(function(){
    Route::apiResource('categories',CategoryController::class);
    Route::apiResource('tasks',TaskController::class);
});
