<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketTypeController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{event}', [EventController::class, 'show']);
Route::post('/events', [EventController::class, 'store'])->middleware('auth:sanctum');
Route::put('/events/{event}', [EventController::class, 'update'])->middleware('auth:sanctum')->can('update', 'event');
Route::patch('/events/{event}', [EventController::class, 'update'])->middleware('auth:sanctum')->can('update', 'event');
Route::delete('/events/{event}', [EventController::class, 'destroy'])->middleware('auth:sanctum')->can('delete', 'event');

Route::get('/ticket-types', [TicketTypeController::class, 'index']);
Route::get('/ticket-types/{ticketType}', [TicketTypeController::class, 'show']);
Route::post('/ticket-types', [TicketTypeController::class, 'store'])->middleware('auth:sanctum');
