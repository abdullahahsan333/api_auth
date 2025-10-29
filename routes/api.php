<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\SubscriptionPlanController;
use App\Http\Controllers\API\UserSubscriptionController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/subscription-plans', [SubscriptionPlanController::class, 'index']);
    Route::post('/subscription-plans', [SubscriptionPlanController::class, 'store']);
    Route::get('/subscription-plans/{id}', [SubscriptionPlanController::class, 'show']);
    Route::put('/subscription-plans/{id}', [SubscriptionPlanController::class, 'update']);
    Route::delete('/subscription-plans/{id}', [SubscriptionPlanController::class, 'destroy']);

    Route::post('/subscribe', [UserSubscriptionController::class, 'subscribe']);
    Route::get('/my-subscriptions', [UserSubscriptionController::class, 'mySubscriptions']);
});
