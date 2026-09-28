<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiSolarController;
use App\Http\Controllers\ApprovalSolarController;
use App\Http\Controllers\MasterPenyimpananController;
use App\Http\Controllers\MasterPenggunaanController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StokSolarController;
use App\Http\Controllers\LogAktivitasController; // <-- UBAH IMPORT INI

Route::get('/', function () {
    // Langsung arahkan ke login agar rapi
    return redirect()->route('login');
});

// --- RUTE AUTENTIKASI ---
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- RUTE YANG BUTUH LOGIN ---
Route::middleware('auth')->group(function () {
    
    // 1. RUTE SPESIFIK (Bisa diakses Semua Role)
    Route::get('/solar/stok', [StokSolarController::class, 'index'])->name('solar.stok');
    
    // ▼▼▼ UBAH BARIS INI MENJADI LOG AKTIVITAS ▼▼▼
    Route::get('/solar/log-aktivitas', [LogAktivitasController::class, 'index'])->name('log.aktivitas');
    // ▲▲▲ ▲▲▲ ▲▲▲ ▲▲▲ ▲▲▲ ▲▲▲ ▲▲▲ ▲▲▲ ▲▲▲ ▲▲▲ ▲▲▲

    // 2. RUTE KHUSUS ADMIN (Menggunakan Middleware IsAdmin)
    Route::middleware([\App\Http\Middleware\IsAdmin::class])->group(function () {
        
        // Approval Pengeluaran
        Route::get('/solar/approval', [ApprovalSolarController::class, 'index'])->name('solar.approval');
        Route::put('/solar/approval/{id}', [ApprovalSolarController::class, 'updateStatus'])->name('solar.approval.update');
        
        // Update Stok Fisik
        Route::put('/solar/stok/{id}', [StokSolarController::class, 'update'])->name('solar.stok.update');
        
        // Master Data
        Route::resource('penyimpanan', MasterPenyimpananController::class)->only(['index', 'store', 'destroy']);
        Route::resource('penggunaan', MasterPenggunaanController::class)->only(['index', 'store']);
    });

    // 3. RUTE RESOURCE (Bisa diakses Semua Role, WAJIB PALING BAWAH)
    Route::resource('solar', TransaksiSolarController::class);

});