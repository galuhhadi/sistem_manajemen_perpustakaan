<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:api', \App\Http\Middleware\LogActivity::class])->group(function () {
    // Route yang butuh login & log ditaruh di sini
});
