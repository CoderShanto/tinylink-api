<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UrlController;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    
    Route::post('/urls', [UrlController::class, 'store']);
    Route::get('/urls', [UrlController::class, 'index']);
    Route::get('/urls/{url}', [UrlController::class, 'show']);
    Route::delete('/urls/{url}', [UrlController::class, 'destroy']); // <--- নতুন Route
});