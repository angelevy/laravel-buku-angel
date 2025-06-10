<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BukuController;

Route::get('/bukus/{user_id}', [BukuController::class, 'index']);
Route::post('/bukus', [BukuController::class, 'store']);
Route::put('/bukus/{buku}', [BukuController::class, 'update']);
Route::delete('/bukus/{buku}', [BukuController::class, 'destroy']);
