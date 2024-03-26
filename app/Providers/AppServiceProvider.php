<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \Laravel\Sanctum\Sanctum::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        View::composer('*', function ($view) {
            // Cek kondisi mengirim data kecuali halaman login
            if (!in_array($view->getName(), ['login'])) {
                // Ambil data user authorized
                $user = session()->get('user')->nip;

                // Ambil data disposisi sesuai nip user yang login 
                $allDisposisi = DB::table('disposisi')
                    ->where('nip_penerima', $user)
                    ->join('users', 'disposisi.nip_penerima', '=', 'users.nip')
                    ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')

                    // Ambil data pengirim disposisi
                    ->leftJoin('users as user_pengirim', 'disposisi.nip_pengirim', '=', 'user_pengirim.nip')
                    ->leftJoin('jabatans as jabatan_pengirim', 'user_pengirim.id_jabatan', '=', 'jabatan_pengirim.id')

                    ->join('tindak_lanjut', 'disposisi.id_tindak_lanjut', '=', 'tindak_lanjut.id')
                    ->select('disposisi.*', 'users.name as nama_penerima', 'jabatans.nama_jabatan as jabatan_penerima', 'user_pengirim.name as nama_pengirim', 'jabatan_pengirim.nama_jabatan as jabatan_pengirim', 'tindak_lanjut.deskripsi',)
                    ->orderByDesc('tanggal_disposisi')
                    ->get();

                foreach ($allDisposisi as $disposisi) {
                    if ($disposisi->nip_penerima === $user) {
                        $disposisi->jabatan_penerima = 'Anda';
                    }

                    // Waktu yang ingin dibandingkan
                    $waktuLain = Carbon::parse($disposisi->tanggal_disposisi);

                    // Hitung selisih waktu
                    $selisihWaktu = Carbon::now()->diffForHumans($waktuLain);

                    // Replace "setelahnya" with "yang lalu"
                    $selisihWaktu = str_replace('setelahnya', 'yang lalu', $selisihWaktu);

                    // Tambahkan kolom selisih_waktu ke dalam data disposisi
                    $disposisi->selisih_waktu = $selisihWaktu;
                }

                $view->with([
                    'allDisposisi' => $allDisposisi,
                ]);
            }
        });
    }
}
