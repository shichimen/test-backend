<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PeopleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('me', [AuthController::class, 'getMe']);
    Route::put('me', [AuthController::class, 'putMe']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::apiResource('people', PeopleController::class)->except('store', 'update', 'destroy');
