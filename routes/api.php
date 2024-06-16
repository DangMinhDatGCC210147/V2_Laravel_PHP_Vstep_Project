<?php

use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;


Route::post('/students/tests/{test}/api/session/start', [SessionController::class, 'start']);
Route::post('/students/tests/{test}/api/session/end', [SessionController::class, 'end']);
Route::get('/api/session/total_hours', [SessionController::class, 'getTotalActiveHours']);
