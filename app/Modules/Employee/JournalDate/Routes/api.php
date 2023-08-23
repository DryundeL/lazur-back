<?php

use App\Modules\Employee\JournalDate\Controllers\JournalDateController;

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

Route::apiResource('/journal_dates', JournalDateController::class)->except('update', 'destroy', 'store');
