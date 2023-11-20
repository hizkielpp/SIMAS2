<?php

use App\Http\Controllers\Surat;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['checkAuth', 'hariKerja'])->group(function () {
    Route::get('/', [AuthController::class, 'dashboard'])->name('dashboard');

    Route::get('/downloadNaskah', [Surat::class, 'downloadNaskah'])->name('downloadNaskah');

    //refresh datatable
    Route::get('/refreshDatatable', [Surat::class, 'refreshDatatable'])->name('refreshDatatable');

    //hanya pak Mul
    Route::middleware('checkAdmin')->group(function () {
        Route::post('/deleteSM', [Surat::class, 'deleteSM'])->name('deleteSM');
        Route::post('/deleteSK', [Surat::class, 'deleteSK'])->name('deleteSK');
    });

    // Surat Masuk
    Route::get('/suratMasuk', [Surat::class, 'indexSM'])->name('suratMasuk');
    Route::get('/getSM/{id}', [Surat::class, 'getSM'])->name('getSM');
    Route::post('/editSM', [Surat::class, 'editSM'])->name('editSM');
    Route::get('/refreshSM', [Surat::class, 'refreshSM'])->name('refreshSM');
    Route::post('/inputSM', [Surat::class, 'inputSM'])->name('inputSM');
    Route::get('/disposisi', [Surat::class, 'disposisi'])->name('disposisi');

    // Surat Keluar dan Antidatir
    Route::get('/getSK/{id}', [Surat::class, 'getSK'])->name('getSK');
    Route::get('/suratKeluar', [Surat::class, 'indexSK'])->name('suratKeluar');
    Route::post('/inputSK', [Surat::class, 'inputSK'])->name('inputSK');
    Route::post('/editSK', [Surat::class, 'editSK'])->name('editSK');
    Route::post('/uploadDokumen', [Surat::class, 'uploadDokumen'])->name('uploadDokumen');
    Route::get('/getSuratKeluar', [Surat::class, 'getSuratKeluar'])->name('getSuratKeluar');
    Route::get('/getSuratAntidatir', [Surat::class, 'getSuratAntidatir'])->name('getSuratAntidatir');

    Route::middleware('checkAdmin')->group(function () {
        // Kelola Akun
        Route::get('/kelolaAkun', [AuthController::class, 'kelolaAkun'])->name('kelolaAkun');
        Route::post('/inputAkun', [AuthController::class, 'inputAkun'])->name('inputAkun');
        Route::post('/editAkun', [AuthController::class, 'editAkun'])->name('editAkun');
        Route::get('/getAkun/{id}', [AuthController::class, 'getAkun'])->name('getAkun');
        Route::post('/deleteAkun', [AuthController::class, 'deleteAkun'])->name('deleteAkun');
    });

    Route::get('/suratAntidatir', [Surat::class, 'indexSA'])->name('suratAntidatir');
    Route::post('/inputSA', [Surat::class, 'inputSA'])->name('inputSA');

    Route::get('/ambilNomor', [Surat::class, 'ambilNomor'])->name('ambilNomor');
    Route::get('/cekTersedia/{id}', [Surat::class, 'cekTersedia'])->name('cekTersedia');
});
Route::post('/login2', [AuthController::class, 'login'])->name('login2');

// Halaman login
Route::middleware('hariKerja')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
});

// Fungsi login
Route::post('/customLogin', [AuthController::class, 'customLogin'])->name('login.custom');

// Halaman logout
Route::get('logout', [AuthController::class, 'signOut'])->name('logout');

if (config('app.env') === 'production') {
    // Lakukan sesuatu di mode pengembangan
    URL::forceScheme('https');
}
