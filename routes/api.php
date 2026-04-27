<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->withoutMiddleware([VerifyCsrfToken::class])->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user'])->middleware('auth');

    Route::post('/register', [UserController::class, 'store']);
    Route::get('/test', function () {
        return response()->json(['message' => 'API funcionando!']);
    });

    Route::middleware('auth')->group(function () {
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::put('/users/{user}', [UserController::class, 'update']);
        Route::patch('/users/{user}', [UserController::class, 'update']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
    });
});

Route::apiResource('vehicles', VehicleController::class);

Route::middleware(['web'])->withoutMiddleware([VerifyCsrfToken::class])->group(function () {
    Route::post('vehicles/{vehicle}/buy', [VehicleController::class, 'buy'])->middleware('auth');
    Route::post('vehicles/{vehicle}/sell', [VehicleController::class, 'sell'])->middleware('auth');
});
