<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'create'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:asmin,asops,kasatker,kaskogartap')->group(function () {
        Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
        Route::post('/notifikasi/{notifikasi}/read', [NotifikasiController::class, 'markRead'])->name('notifikasi.read');
    });

    Route::middleware('role:staf,admin')->group(function () {
        Route::resource('surat-masuk', SuratMasukController::class)->except(['index', 'show']);

        Route::resource('surat-keluar', SuratKeluarController::class)->except(['index', 'show']);
    });

    Route::middleware('role:admin,staf,asmin,asops,kasatker,kaskogartap')->group(function () {
        Route::get('/barcode/{barcode}', [BarcodeController::class, 'show'])->name('barcode.show');

        Route::get('/surat-keluar/{suratKeluar}/download', [SuratKeluarController::class, 'downloadFile'])->name('surat-keluar.download');
        Route::get('/surat-keluar/{suratKeluar}/print', [SuratKeluarController::class, 'print'])->name('surat-keluar.print');
        Route::resource('surat-keluar', SuratKeluarController::class)->only(['index', 'show']);

        Route::get('/surat-masuk/{suratMasuk}/download', [SuratMasukController::class, 'downloadFile'])->name('surat-masuk.download');
        Route::get('/surat-masuk/{suratMasuk}/print', [SuratMasukController::class, 'print'])->name('surat-masuk.print');
        Route::resource('surat-masuk', SuratMasukController::class)->only(['index', 'show']);
    });

    Route::middleware('role:asmin,asops,kasatker,kaskogartap')->group(function () {
        Route::get('/disposisi/inbox', [DisposisiController::class, 'inbox'])->name('disposisi.inbox');
        Route::get('/disposisi/{disposisi}/print', [DisposisiController::class, 'printSheet'])->name('disposisi.print');
    });

    Route::middleware('role:kaskogartap')->group(function () {
        Route::get('/disposisi/outbox', [DisposisiController::class, 'outbox'])->name('disposisi.outbox');
    });

    Route::middleware('role:kaskogartap')->group(function () {
        Route::get('/surat-masuk/{suratMasuk}/disposisi/create', [DisposisiController::class, 'create'])->name('surat-masuk.disposisi.create');
        Route::post('/surat-masuk/{suratMasuk}/disposisi', [DisposisiController::class, 'store'])->name('surat-masuk.disposisi.store');
        Route::post('/surat-masuk/{suratMasuk}/gabungan/regenerate', [DisposisiController::class, 'regenerateGabungan'])->name('surat-masuk.gabungan.regenerate');
    });

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserManagementController::class)->except(['show']);
    });
});
