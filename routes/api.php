<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::get('/events', [EventController::class, 'index']);
Route::post('/events', [EventController::class, 'store']);
Route::get('/events/{event}', [EventController::class, 'show']);
Route::put('/events/{event}', [EventController::class, 'update'])->middleware('auth:sanctum')->can('update', 'event');
Route::patch('/events/{event}', [EventController::class, 'update'])->middleware('auth:sanctum')->can('update', 'event');
Route::delete('/events/{event}', [EventController::class, 'destroy']);
