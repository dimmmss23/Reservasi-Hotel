<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Models\Kamar;

Route::get('/', function () {
    $kamars = Kamar::all();
    return view('landing', compact('kamars'));
});

// ADMIN
Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    
    Route::get('/admin/kamar', [KamarController::class, 'index']);
    Route::get('/admin/kamar/create', [KamarController::class, 'create']);
    Route::post('/admin/kamar', [KamarController::class, 'store']);
    Route::get('/admin/kamar/{id}/edit', [KamarController::class, 'edit']);
    Route::put('/admin/kamar/{id}', [KamarController::class, 'update']);
    Route::delete('/admin/kamar/{id}', [KamarController::class, 'destroy']);
    
    Route::get('/admin/reservasi', [ReservasiController::class, 'indexAdmin']);
    Route::get('/admin/reservasi/{id}', [ReservasiController::class, 'show'])->name('admin.reservasi.show');
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
    Route::get('/tamu/home', [DashboardController::class, 'tamu'])->name('tamu.home');
    
    Route::get('/kamar', [KamarController::class, 'listTamu']);
    Route::get('/kamar/{id}', [KamarController::class, 'detail']);
    Route::get('/kamar/{id}/pesan', [KamarController::class, 'pesan']);
    Route::post('/reservasi', [ReservasiController::class, 'store']);
    Route::get('/reservasi/saya', [ReservasiController::class, 'listUser'])->name('tamu.reservasi.index');
    Route::post('/reservasi/{id}/cancel', [ReservasiController::class, 'cancel']);
    
    // Pembayaran Tamu
    Route::get('/payment/{reservasi}/upload', [PaymentController::class, 'uploadForm'])->name('tamu.payment.upload');
    Route::post('/payment/{reservasi}/upload', [PaymentController::class, 'upload']);
    Route::get('/payment/{reservasi}/show', [PaymentController::class, 'show'])->name('tamu.payment.show');
});

Auth::routes();

Route::get('/home', [DashboardController::class, 'tamu'])->middleware('auth')->name('home');
