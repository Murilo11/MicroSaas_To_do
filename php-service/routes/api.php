<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardController;
use App\Http\Controllers\BoardController;


Route::get('/health', function () {
    return response()->json([
        'status' => 'Microsserviço PHP está rodando!',
        'time' => now()->toDateTimeString()
    ]);
});

Route::middleware(['auth.jwt'])->group(function () {

    Route::get('/user-check', function (Request $request) {
        return response()->json([
            'message' => 'Token JWT válido!',
            'user' => $request->attributes->get('user')
        ]);
    });

    Route::apiResource('boards', BoardController::class);
    Route::apiResource('cards', CardController::class);

});



