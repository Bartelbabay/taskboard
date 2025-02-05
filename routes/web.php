<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::post('/api/login', [AuthController::class, 'login']);
Route::get('/api/check-auth', [AuthController::class, 'checkAuth']);
Route::middleware('auth')->group(function () {
    Route::get('/api/tasks', [TaskController::class, 'index']);
    Route::post('/api/tasks', [TaskController::class, 'store']);
    Route::put('/api/tasks/{id}', [TaskController::class, 'update']);
    Route::get('/api/tasks/{id}', [TaskController::class, 'get']);
    Route::put('/api/tasks/{id}/toggle-complete', [TaskController::class, 'toggleComplete']);
    Route::delete('/api/tasks/{id}', [TaskController::class, 'destroy']);
});
