<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Halaman aplikasi (wajib login)
Route::middleware('auth')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    // Manajemen karyawan (tanpa hapus permanen — status nonaktif dipakai untuk menjaga riwayat).
    Route::resource('employees', EmployeeController::class)->except(['destroy']);
    Route::patch('employees/{employee}/status', [EmployeeController::class, 'toggleStatus'])->name('employees.status');

    // Data master: brand / divisi / lokasi / jabatan.
    Route::resource('brands', BrandController::class)->except(['show']);
    Route::resource('divisions', DivisionController::class)->except(['show']);
    Route::resource('locations', LocationController::class)->except(['show']);
    Route::resource('positions', PositionController::class)->except(['show']);

    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/export', [ReportController::class, 'export'])->name('reports.export');
    Route::get('reports/pdf', [ReportController::class, 'exportPdf'])->name('reports.pdf');
});
