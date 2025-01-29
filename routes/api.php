<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OrganisationController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('sanctum/token', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);

    Route::apiResource('organisations', OrganisationController::class)->only(['index', 'show']);
});
