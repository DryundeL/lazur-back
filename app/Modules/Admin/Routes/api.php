<?php

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

Route::prefix('auth')->group(function () {
    require base_path('app/Modules/Admin/Auth/Routes/api.php');
});

Route::middleware('auth:admins')->group(function () {
    require base_path('app/Modules/Admin/Student/Routes/api.php');
    require base_path('app/Modules/Admin/Employee/Routes/api.php');
    require base_path('app/Modules/Admin/Group/Routes/api.php');
    require base_path('app/Modules/Admin/Speciality/Routes/api.php');
    require base_path('app/Modules/Admin/Audience/Routes/api.php');
    require base_path('app/Modules/Admin/Discipline/Routes/api.php');
    require base_path('app/Modules/Admin/ClassTime/Routes/api.php');
    require base_path('app/Modules/Admin/Semester/Routes/api.php');
    require base_path('app/Modules/Admin/Holiday/Routes/api.php');
    require base_path('app/Modules/Admin/Schedule/Routes/api.php');
});

