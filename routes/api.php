<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\GetUserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Timetables\ShowTimetableController;
use App\Http\Controllers\Timetables\ListTimetablesController;
use App\Http\Controllers\Timetables\CreateTimetableController;

Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', GetUserController::class);
    Route::post('/logout', LogoutController::class);
});

Route::middleware('auth:sanctum')->post('/timetables', CreateTimetableController::class);
Route::middleware('auth:sanctum')->get('/timetables/{timetable}', ShowTimetableController::class);
Route::middleware('auth:sanctum')->get('/timetables', ListTimetablesController::class);

