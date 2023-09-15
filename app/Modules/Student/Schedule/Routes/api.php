<?php

use App\Modules\Student\Schedule\Controllers\ScheduleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::get('/schedules', [ScheduleController::class, 'index']);
Route::get('/schedules/export', [ScheduleController::class, 'export']);
