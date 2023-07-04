<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Group\Controllers;

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

Route::apiResource('/groups', Controllers\GroupController::class);
Route::apiResource('/groups.students', Controllers\GroupStudentController::class)->shallow()->except(['update', 'show', 'destroy']);
Route::put('/groups/{group}/students', [Controllers\GroupStudentController::class, 'update']);
