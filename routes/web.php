<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\Diagnosis\DiagnosaGiziController;
use App\Http\Controllers\Intervensi\PreskripsiDietController;
use App\Http\Controllers\Monitoring\MonitoringController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\Admin\PenggunaController;

Route::get('/', function () { return redirect()->route('login'); });

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'session.timeout'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::middleware('role:perawat,nutrisionis,dietisien,spgk')->group(function() {
        Route::resource('pasien', PasienController::class);
        Route::post('/pasien/{pasien}/kunjungan', [PasienController::class, 'storeKunjungan'])->name('pasien.kunjungan.store');
        Route::get('/kunjungan/{kunjungan}', [KunjunganController::class, 'show'])->name('kunjungan.show');
    });

    Route::middleware('role:perawat,spgk')->group(function() {
        Route::post('/kunjungan/{kunjungan}/skrining', [KunjunganController::class, 'storeSkrining'])->name('kunjungan.skrining.store');
    });

    Route::middleware('role:perawat,dietisien,spgk')->group(function() {
        Route::post('/kunjungan/{kunjungan}/fisik', [KunjunganController::class, 'storeFisik'])->name('kunjungan.fisik.store');
    });

    Route::middleware('role:nutrisionis,dietisien,spgk')->group(function() {
        Route::post('/kunjungan/{kunjungan}/antropometri', [KunjunganController::class, 'storeAntropometri'])->name('kunjungan.antropometri.store');
        Route::post('/kunjungan/{kunjungan}/asupan', [KunjunganController::class, 'storeAsupan'])->name('kunjungan.asupan.store');
    });

    Route::middleware('role:dietisien,spgk')->group(function() {
        Route::post('/kunjungan/{kunjungan}/biokimia', [KunjunganController::class, 'storeBiokimia'])->name('kunjungan.biokimia.store');
        Route::post('/kunjungan/{kunjungan}/selesai', [KunjunganController::class, 'selesaikan'])->name('kunjungan.selesai');
    });

    Route::middleware('role:dietisien,spgk')->group(function() {
        Route::get('/diagnosis', [DiagnosaGiziController::class, 'index'])->name('diagnosis.index');
        Route::post('/diagnosis', [DiagnosaGiziController::class, 'store'])->name('diagnosis.store');
        Route::post('/diagnosis/{diagnosaGizi}/validasi', [DiagnosaGiziController::class, 'validasi'])->name('diagnosis.validasi');
        Route::get('/intervensi', [PreskripsiDietController::class, 'index'])->name('intervensi.index');
        Route::post('/intervensi', [PreskripsiDietController::class, 'store'])->name('intervensi.store');
        Route::post('/intervensi/{preskripsiDiet}/setujui', [PreskripsiDietController::class, 'setujui'])->name('intervensi.setujui');
        Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
        Route::post('/monitoring', [MonitoringController::class, 'store'])->name('monitoring.store');
        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::post('/kunjungan/{kunjungan}/kunci', [KunjunganController::class, 'kunci'])->name('kunjungan.kunci');
    });

    Route::middleware('role:admin_ti')->group(function() {
        Route::resource('/admin/pengguna', PenggunaController::class)->names('admin.pengguna');
    });
});
