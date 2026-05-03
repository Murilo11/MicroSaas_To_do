<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CardController; 


Route::get('/health', function () {
    return response()->json([
        'status' => 'Microsserviço PHP está rodando!',
        'time' => now()->toDateTimeString()
    ]);
});

Route::middleware(['auth.jwt'])->group(function () {
    
    Route::post('/cards', [CardController::class, 'store']);

    Route::get('/user-check', function (Request $request) {
        return response()->json([
            'message' => 'Token JWT válido!',
            'user' => $request->attributes->get('user')
        ]);
    });

});
use App\Http\Controllers\BoardController;

Route::apiResource('boards', BoardController::class);
Route::apiResource('cards', CardController::class);
