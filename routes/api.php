<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TreeController;

Route::post('/register', [\App\Http\Controllers\Api\V1\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\V1\AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

Route::post('/trees', [TreeController::class, 'store']);
Route::get('/trees', [TreeController::class, 'index']);
Route::get('/trees/{id}', [TreeController::class, 'show']);
Route::delete('/trees/{id}', [TreeController::class, 'destroy']);


Route::post('/trees/{tree}/care', [TreeController::class, 'care']); 

});