<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminClaimController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminHandoverController;
use App\Http\Controllers\Admin\AdminHistoryController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminSatpamController;
use App\Http\Controllers\Admin\AdminVerificationController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Form Lapor
Route::get('/lapor/hilang', [ReportController::class, 'createHilang'])->name('report.create.hilang');
Route::get('/lapor/ditemukan', [ReportController::class, 'createDitemukan'])->name('report.create.ditemukan');
Route::post('/lapor', [ReportController::class, 'store'])->name('report.store');
Route::get('/lapor/sukses/{code}', [ReportController::class, 'success'])->name('report.success');

// Detail Laporan
Route::get('/laporan/{code}', [ReportController::class, 'show'])->name('report.show');

// Cek & Edit Laporan
Route::get('/cek', [ReportController::class, 'check'])->name('report.check');
Route::post('/cek/lacak', [ReportController::class, 'track'])->name('report.track');
Route::post('/cek/edit', [ReportController::class, 'editForm'])->name('report.editForm');
Route::put('/laporan/edit/{code}', [ReportController::class, 'update'])->name('report.update');
Route::delete('/laporan/hapus/{code}', [ReportController::class, 'destroy'])->name('report.destroy');

// Klaim Barang
Route::get('/klaim/{code}', [ClaimController::class, 'create'])->name('claim.create');
Route::post('/klaim/{code}', [ClaimController::class, 'store'])->name('claim.store');
Route::get('/klaim/sukses/{claim_code}', [ClaimController::class, 'success'])->name('claim.success');
Route::post('/cek/klaim', [ClaimController::class, 'checkStatus'])->name('claim.checkStatus');

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/

// Fallback standard route for unauthenticated redirects
Route::redirect('/login', '/admin/login')->name('login');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'authenticate'])->name('admin.authenticate');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

    Route::middleware('auth:admin')->group(function () {
        // Dashboard (Accessible by Admin and Satpam)
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

        // Manage Reports
        Route::get('/laporan/{id}', [AdminReportController::class, 'show'])->name('admin.report.show');
        Route::post('/laporan/{id}/status', [AdminReportController::class, 'updateStatus'])->name('admin.report.status');
        Route::delete('/laporan/{id}', [AdminReportController::class, 'destroy'])->name('admin.report.destroy');

        // Manage Claims
        Route::post('/klaim/{id}/tindak-lanjuti', [AdminClaimController::class, 'tindakLanjuti'])->name('admin.claim.approve');
        Route::post('/klaim/{id}/batalkan', [AdminClaimController::class, 'batalkan'])->name('admin.claim.cancel');

        // Handover (Serah Terima - Satpam process)
        Route::get('/serah-terima', [AdminHandoverController::class, 'index'])->name('admin.handover.index');
        Route::get('/serah-terima/proses/{id}', [AdminHandoverController::class, 'create'])->name('admin.handover.create');
        Route::post('/serah-terima/proses/{id}', [AdminHandoverController::class, 'store'])->name('admin.handover.store');
        Route::post('/serah-terima/batal/{id}', [AdminHandoverController::class, 'cancelQueue'])->name('admin.handover.cancelQueue');

        // Admin ONLY Routes
        Route::middleware('admin.role:super_admin')->group(function () {
            // Verifikasi Serah Terima
            Route::get('/verifikasi', [AdminVerificationController::class, 'index'])->name('admin.verification.index');
            Route::get('/verifikasi/{id}', [AdminVerificationController::class, 'show'])->name('admin.verification.show');
            Route::post('/verifikasi/{id}/approve', [AdminVerificationController::class, 'approve'])->name('admin.verification.approve');
            Route::post('/verifikasi/{id}/reject', [AdminVerificationController::class, 'reject'])->name('admin.verification.reject');

            // CRUD Akun Satpam
            Route::get('/satpam', [AdminSatpamController::class, 'index'])->name('admin.satpam.index');
            Route::get('/satpam/tambah', [AdminSatpamController::class, 'create'])->name('admin.satpam.create');
            Route::post('/satpam', [AdminSatpamController::class, 'store'])->name('admin.satpam.store');
            Route::get('/satpam/{id}/edit', [AdminSatpamController::class, 'edit'])->name('admin.satpam.edit');
            Route::put('/satpam/{id}', [AdminSatpamController::class, 'update'])->name('admin.satpam.update');
            Route::delete('/satpam/{id}', [AdminSatpamController::class, 'destroy'])->name('admin.satpam.destroy');

            // History (Khusus Admin)
            Route::get('/history', [AdminHistoryController::class, 'index'])->name('admin.history.index');
            Route::get('/history/{id}', [AdminHistoryController::class, 'show'])->name('admin.history.show');
        });
    });
});
