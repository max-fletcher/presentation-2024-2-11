<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::get('/method-chaining', [UserController::class, 'getStudent']);

Route::patch('/request-all/{id}', [UserController::class, 'requestAll']);

Route::post('/using-service-class', [UserController::class, 'usingServiceClass']);

Route::get('/joins-vs-subquery', [UserController::class, 'joinsVsSubquery']);

Route::patch('/validate-with-try-catch/{id}', [UserController::class, 'validateWithTryCatch']);