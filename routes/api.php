<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventCounterController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Countdown API routes with relaxed rate limiting (hourly updates)
Route::middleware(['throttle:60,60'])->group(function () {
    Route::get('/countdown/{eventId}', [EventCounterController::class, 'getCountdownData']);
    Route::get('/countdown', [EventCounterController::class, 'getAllCountdownData']);
});
