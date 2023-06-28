<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Admin\Auth\Controllers;

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

Route::post('/login', [Controllers\AuthController::class, 'login'])->name('admin.auth.login');

Route::middleware('auth:admins')->group(function () {
    Route::delete('/logout', [Controllers\AuthController::class, 'logout'])->name('admin.auth.logout');
});

