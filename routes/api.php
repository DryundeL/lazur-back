<?php

use Illuminate\Http\Request;
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


Route::prefix('admin')->group(function () {
    require base_path('app/Modules/Admin/Routes/api.php');
});

Route::prefix('employee')->group(function () {
    require base_path('app/Modules/Employee/Routes/api.php');
});

Route::prefix('student')->group(function () {
    require base_path('app/Modules/Student/Routes/api.php');
});

