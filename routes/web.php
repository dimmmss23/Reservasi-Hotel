<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\UserController;
use App\Models\Kamar;

Route::get('/', function () {
    $kamars = Kamar::all();
    return view('landing', compact('kamars'));
});

// ADMIN
Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->group(function () {
    Route::get('/admin/kamar', [KamarController::class, 'index']);
    Route::get('/admin/kamar/create', [KamarController::class, 'create']);
    Route::post('/admin/kamar', [KamarController::class, 'store']);
    Route::get('/admin/kamar/{id}/edit', [KamarController::class, 'edit']);
    Route::put('/admin/kamar/{id}', [KamarController::class, 'update']);
    Route::delete('/admin/kamar/{id}', [KamarController::class, 'destroy']);
    
    Route::get('/admin/reservasi', [ReservasiController::class, 'indexAdmin']);
    Route::post('/admin/reservasi/{id}/approve', [ReservasiController::class, 'approve']);
    Route::post('/admin/reservasi/{id}/reject', [ReservasiController::class, 'reject']);
    Route::post('/admin/reservasi/{id}/checkout', [ReservasiController::class, 'checkout']);
    
    // Kelola User
    Route::get('/admin/user', [UserController::class, 'index']);
    Route::get('/admin/user/create', [UserController::class, 'create']);
    Route::post('/admin/user', [UserController::class, 'store']);
    Route::get('/admin/user/{id}/edit', [UserController::class, 'edit']);
    Route::put('/admin/user/{id}', [UserController::class, 'update']);
    Route::delete('/admin/user/{id}', [UserController::class, 'destroy']);
});

// TAMU
Route::middleware(['auth'])->group(function () {
    Route::get('/kamar', [KamarController::class, 'listTamu']);
    Route::get('/kamar/{id}', [KamarController::class, 'detail']);
    Route::get('/kamar/{id}/pesan', [KamarController::class, 'pesan']);
    Route::post('/reservasi', [ReservasiController::class, 'store']);
    Route::get('/reservasi/saya', [ReservasiController::class, 'listUser']);
    Route::post('/reservasi/{id}/cancel', [ReservasiController::class, 'cancel']);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
