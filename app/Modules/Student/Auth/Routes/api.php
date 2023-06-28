<?php

use App\Modules\Student\Auth\Controllers;
use Illuminate\Support\Facades\Route;

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

Route::post('/login', [Controllers\AuthController::class, 'login'])->name('student.auth.login');

Route::middleware('auth:students')->group(function () {
    Route::delete('/logout', [Controllers\AuthController::class, 'logout'])->name('student.auth.logout');
});

