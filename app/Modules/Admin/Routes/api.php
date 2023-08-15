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
    $modules = config('modules.modules');

    foreach ($modules as $module => $subModules) {
        foreach ($subModules as $subModule) {
            require base_path("app/Modules/{$module}/{$subModule}/Routes/api.php");
        }
    }

});

