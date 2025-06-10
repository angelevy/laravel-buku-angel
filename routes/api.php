<?php

use App\Http\Controllers\BukuController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/buku-api', [BukuController::class, 'index'])->name('show');
Route::get('/buku-api/create', [BukuController::class, 'create'])->name('create');
Route::post('/buku-api/store', [BukuController::class, 'store'])->name('store');

Route::POST('/buku-api/edit/{id}', [BukuController::class, 'update'])->name('update');

Route::delete('/buku-api/delete/{id}', [BukuController::class, 'destroy'])->name('destroy');
