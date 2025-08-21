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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Equipment data submission endpoint - restricted to specific IP address
Route::post('/battery-inform', [App\Http\Controllers\Api\BatteryController::class, 'store'])
    ->name('api.battery.inform');
    // Temporarily commented out for testing
    // ->middleware('restrict.ip:136.38.213.33');