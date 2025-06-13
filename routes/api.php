<?php

use App\Http\Controllers\BukuController;
use Illuminate\Support\Facades\Route;

Route::get('buku', [BukuController::class, 'index']);
Route::get('buku/{id}', [BukuController::class, 'show']);
Route::post('buku', [BukuController::class, 'store']);
Route::put('buku/{id}', [BukuController::class, 'update']);
Route::delete('buku/{id}', [BukuController::class, 'destroy']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

