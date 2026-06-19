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
        Route::get('/pasien/{pasien}/cppt', [PasienController::class, 'cppt'])->name('pasien.cppt');
        Route::post('/pasien/{pasien}/kunjungan', [PasienController::class, 'storeKunjungan'])->name('pasien.kunjungan.store');
        Route::get('/kunjungan/{kunjungan}', [KunjunganController::class, 'show'])->name('kunjungan.show');
        Route::get('/kunjungan/{kunjungan}/cetak-pagt', [App\Http\Controllers\Export\ExportController::class, 'cetakPagt'])->name('kunjungan.cetak-pagt');
    });

    Route::middleware('role:perawat,spgk')->group(function() {
        Route::post('/kunjungan/{kunjungan}/skrining', [KunjunganController::class, 'storeSkrining'])->name('kunjungan.skrining.store');
    });
    Route::middleware('role:perawat,dietisien,spgk')->group(function() {
        Route::post('/kunjungan/{kunjungan}/fisik', [KunjunganController::class, 'storeFisik'])->name('kunjungan.fisik.store');
    });

    Route::middleware('role:perawat,nutrisionis,dietisien,spgk')->group(function() {
        Route::get('/kunjungan/{kunjungan}', [KunjunganController::class, 'show'])->name('kunjungan.show');
        Route::post('/kunjungan/{kunjungan}/kunci', [KunjunganController::class, 'kunciDokumen'])->name('kunjungan.kunci');
        Route::post('/kunjungan/{kunjungan}/antropometri', [KunjunganController::class, 'storeAntropometri'])->name('kunjungan.antropometri.store');
        Route::post('/kunjungan/{kunjungan}/asupan', [KunjunganController::class, 'storeAsupan'])->name('kunjungan.asupan.store');
        Route::post('/kunjungan/{kunjungan}/konseling', [KunjunganController::class, 'storeKonseling'])->name('kunjungan.konseling.store');
        Route::post('/kunjungan/{kunjungan}/obat', [KunjunganController::class, 'updateObat'])->name('kunjungan.obat.update');
        Route::post('/kunjungan/{kunjungan}/adendum', [KunjunganController::class, 'storeAdendum'])->name('kunjungan.adendum.store');
    });

    Route::middleware('role:dietisien,spgk')->group(function() {
        Route::post('/kunjungan/{kunjungan}/biokimia', [KunjunganController::class, 'storeBiokimia'])->name('kunjungan.biokimia.store');
        Route::post('/kunjungan/{kunjungan}/selesai', [KunjunganController::class, 'selesaikan'])->name('kunjungan.selesai');
        Route::post('/kunjungan/{kunjungan}/kritis', [KunjunganController::class, 'storePreskripsiKritis'])->name('kunjungan.kritis.store');
    });

    Route::middleware('role:dietisien,spgk')->group(function() {
        Route::get('/diagnosis', [DiagnosaGiziController::class, 'index'])->name('diagnosis.index');
        Route::post('/diagnosis', [DiagnosaGiziController::class, 'store'])->name('diagnosis.store');
        Route::get('/intervensi', [PreskripsiDietController::class, 'index'])->name('intervensi.index');
        Route::post('/intervensi', [PreskripsiDietController::class, 'store'])->name('intervensi.store');
        Route::post('/intervensi/{preskripsiDiet}/menu', [KunjunganController::class, 'storeMenuHarian'])->name('intervensi.menu.store');
        Route::post('/kunjungan/{kunjungan}/dokumen-edukasi', [KunjunganController::class, 'storeDokumenEdukasi'])->name('kunjungan.dokumen-edukasi.store');
        Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
        Route::post('/monitoring', [MonitoringController::class, 'store'])->name('monitoring.store');
        Route::get('/laporan', [App\Http\Controllers\Laporan\LaporanController::class, 'index'])->name('laporan.index');

        // Dapur / Food Service
        Route::get('/dapur/permintaan-makanan', [App\Http\Controllers\Dapur\FoodServiceController::class, 'index'])->name('dapur.index');
        Route::get('/dapur/cetak-etiket/{id}', [App\Http\Controllers\Dapur\FoodServiceController::class, 'cetakEtiket'])->name('dapur.cetak');
        Route::resource('/bahan-makanan', \App\Http\Controllers\BahanMakananController::class)->except(['create','show','edit']);
    });

    Route::middleware('role:spgk')->group(function() {
        Route::post('/diagnosis/{diagnosaGizi}/validasi', [DiagnosaGiziController::class, 'validasi'])->name('diagnosis.validasi');
        Route::post('/intervensi/{preskripsiDiet}/setujui', [PreskripsiDietController::class, 'setujui'])->name('intervensi.setujui');
        Route::post('/kunjungan/{kunjungan}/kunci', [KunjunganController::class, 'kunci'])->name('kunjungan.kunci');
    });

    Route::middleware('role:admin_ti')->group(function() {
        Route::resource('/admin/pengguna', PenggunaController::class)->names('admin.pengguna');
    });

    Route::get('/export/pasien/pdf', [\App\Http\Controllers\Export\ExportController::class, 'exportPasienPdf'])->name('export.pasien.pdf');
    Route::get('/export/laporan/excel', [\App\Http\Controllers\Export\ExportController::class, 'exportLaporanExcel'])->name('export.laporan.excel');
});
