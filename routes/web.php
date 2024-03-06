<?php

use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Surat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\SuratMasukController;

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
    });
    Route::post('/deleteSK', [Surat::class, 'deleteSK'])->name('deleteSK');

    // Surat masuk start
    Route::controller(SuratMasukController::class)->group(function () {
        Route::name('surat-masuk.')->group(function () {
            Route::get('/surat-masuk', 'index')->name('index');
            Route::get('/getSM/{id}', 'getSM')->name('getSM');
            Route::get('/surat-masuk/detail/{id}', 'show')->name('show');
            Route::post('/inputSM', 'inputSM')->name('inputSM');
            Route::post('/editSM', 'editSM')->name('editSM');
            Route::post('/surat-masuk/detail/store-disposisi/{id}', 'disposisiStore')->name('disposisiStore');
            Route::post('/surat-masuk/detail/end-disposisi/{id}', 'disposisiEnd')->name('disposisiEnd');
        });
    });
    // Surat masuk end

    // Surat Keluar dan Antidatir
    Route::get('/getSK/{id}', [Surat::class, 'getSK'])->name('getSK');
    Route::get('/suratKeluar', [Surat::class, 'indexSK'])->name('suratKeluar');
    Route::post('/inputSK', [Surat::class, 'inputSK'])->name('inputSK');
    Route::post('/editSK', [Surat::class, 'editSK'])->name('editSK');
    Route::post('/uploadDokumen', [Surat::class, 'uploadDokumen'])->name('uploadDokumen');
    Route::get('/getSuratKeluar', [Surat::class, 'getSuratKeluar'])->name('getSuratKeluar');
    Route::get('/getSuratAntidatir', [Surat::class, 'getSuratAntidatir'])->name('getSuratAntidatir');
    Route::get('/cekTersediaDatepicker', [Surat::class, 'cekTersediaDatepicker'])->name('cekTersediaDatepicker');

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
