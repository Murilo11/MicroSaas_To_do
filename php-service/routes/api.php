<?php

use App\Http\Controllers\BoardController;
use App\Http\Controllers\CardController;
use Illuminate\Support\Facades\Route;

Route::apiResource('boards', BoardController::class);
Route::apiResource('cards', CardController::class);
