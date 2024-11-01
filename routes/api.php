<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/events', [EventController::class, 'index']);
Route::post('/events', [EventController::class, 'store']);
Route::get('/events/{event}', [EventController::class, 'show']);
Route::put('/events/{event}', [EventController::class, 'update'])->middleware('auth:sanctum')->can('update', 'event');
Route::patch('/events/{event}', [EventController::class, 'update'])->middleware('auth:sanctum')->can('update', 'event');
Route::delete('/events/{event}', [EventController::class, 'destroy'])->middleware('auth:sanctum')->can('delete', 'event');
