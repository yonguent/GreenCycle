<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TreeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('api/trees/', [TreeController::class, 'index']);
Route::get('api/trees/{id}', [TreeController::class, 'show']);
