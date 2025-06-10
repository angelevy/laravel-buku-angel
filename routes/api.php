<?php

use App\Http\Controllers\BukuController;
use Illuminate\Support\Facades\Route;

Route::get('/buku', [BukuController::class, 'index']); // GET data
Route::get('/buku/{id}', [BukuController::class, 'show']); // GET data
Route::post('/buku/create', [BukuController::class, 'store']); // POST data
Route::post('/buku/{id}', [BukuController::class, 'update']); // UPDATE data (pakai POST karena form-data)
Route::delete('/buku/{id}', [BukuController::class, 'destroy']); // DELETE data

