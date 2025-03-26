<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\GetUserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Activities\ShowActivityController;
use App\Http\Controllers\Timetables\ShowTimetableController;
use App\Http\Controllers\Activities\CreateActivityController;
use App\Http\Controllers\Activities\DeleteActivityController;
use App\Http\Controllers\Activities\ListActivitiesController;
use App\Http\Controllers\Activities\UpdateActivityController;
use App\Http\Controllers\Timetables\ListTimetablesController;
use App\Http\Controllers\Timetables\CreateTimetableController;
use App\Http\Controllers\Timetables\DeleteTimetableController;
use App\Http\Controllers\Timetables\UpdateTimetableController;

Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', GetUserController::class);
    Route::post('/logout', LogoutController::class);
});

Route::prefix('timetables')->middleware('auth:sanctum')->group(function () {
    Route::get('/', ListTimetablesController::class);
    Route::get('/{timetable}', ShowTimetableController::class);
    Route::post('/', CreateTimetableController::class);
    Route::put('/{timetable}', UpdateTimetableController::class);
    Route::delete('/{timetable}', DeleteTimetableController::class);
});

Route::middleware('auth:sanctum')->prefix('timetables/{timetable}/activities')->group(function () {
    Route::get('/', ListActivitiesController::class);
    Route::post('/', CreateActivityController::class);
    Route::get('{activity}', ShowActivityController::class);
    Route::put('{activity}', UpdateActivityController::class);
    Route::delete('{activity}', DeleteActivityController::class);
});

