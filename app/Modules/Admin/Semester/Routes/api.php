<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Semester\Controllers;

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

Route::apiResource('/semesters', Controllers\SemesterController::class);

