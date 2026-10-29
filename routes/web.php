<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PpdbController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SiswaAuthController;
use App\Http\Controllers\SiswaController;

// Publik
Route::get('/', [PpdbController::class, 'index'])->name('ppdb.index');
Route::get('/sukses', [PpdbController::class, 'success'])->name('ppdb.success');

// Auth siswa (guest)
Route::middleware('guest')->group(function () {
    Route::get('/login', [SiswaAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [SiswaAuthController::class, 'login'])->name('login.post');
    Route::get('/register', [SiswaAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [SiswaAuthController::class, 'register'])->name('register.post');
});
Route::post('/logout', [SiswaAuthController::class, 'logout'])->middleware('auth')->name('logout');

// Area siswa (butuh login)
Route::middleware('auth')->group(function () {
    Route::get('/daftar', [PpdbController::class, 'form'])->name('ppdb.form');
    Route::post('/daftar', [PpdbController::class, 'store'])->name('ppdb.store');
    Route::get('/pendaftaran/edit', [PpdbController::class, 'edit'])->name('ppdb.edit');
    Route::put('/pendaftaran', [PpdbController::class, 'update'])->name('ppdb.update');
    Route::get('/siswa', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');
});

// Admin (session-based, terpisah dari auth siswa)
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/{applicant}', [AdminController::class, 'show'])->name('admin.show');
    Route::patch('/{applicant}/status', [AdminController::class, 'updateStatus'])->name('admin.status');
    Route::delete('/{applicant}', [AdminController::class, 'destroy'])->name('admin.destroy');
});
