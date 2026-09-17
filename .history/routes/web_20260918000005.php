<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RedirectController;

// ... আগের কোড (যদি থাকে) ...

// Public Short URL Redirect (Must be at the bottom to avoid catching other routes)
Route::get('/{shortCode}', [RedirectController::class, 'redirect'])
     ->where('shortCode', '[A-Za-z0-9]+'); // শুধু alphanumeric short code ধরবে