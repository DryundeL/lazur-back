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
    require base_path('app/Modules/Student/Auth/Routes/api.php');
});

Route::middleware('auth:students')->group(function () {
    $modules = config('modules.modules')['Student'];

    foreach ($modules as $subModule ) {
        require base_path("app/Modules/Student/{$subModule}/Routes/api.php");
    }
});

