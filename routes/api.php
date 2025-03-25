<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\{
    RegisterController,
    LoginController,
    LogoutController,
    GetUserController
};

Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', GetUserController::class);
    Route::post('/logout', LogoutController::class);
});
