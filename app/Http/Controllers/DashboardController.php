<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today();
        $now = Carbon::now();
        $formattedDate = $now->format('Y-m-d');
        $user = session()->get('user');
        $suratAntidatir = DB::table('suratkeluar')
            ->where('jenis', 'antidatir')
            ->where('status', 'digunakan')
            ->get();
        foreach ($suratAntidatir as $item) {
            $tanggal = Carbon::parse($item->updated_at);
            $tanggalDiformat = $tanggal->format('Y-m-d');
            $item->updated_at = $tanggalDiformat;
        }
        $suratKeluar = DB::table('suratkeluar')
            // ->where('jenis', 'biasa')
            ->where('created_by', $user->nip)
            ->join('users', 'suratkeluar.created_by', '=', 'users.nip')
            ->select('suratkeluar.*', 'users.name as name', 'users.bagian as bagian')
            ->orderBy('nomorSurat', 'desc')
            ->get();

        // Mengambil data surat keluar untuk pengecekan jumlah surat yang belum memiliki lampiran start
        if ($user->role_id == 2) {
            $jumlahSM = DB::table('suratmasuk')
                ->where('created_by', $user->nip)
                ->count();
            $jumlahSK = DB::table('suratkeluar')
                ->where('jenis', 'biasa')
                ->where('status', 'digunakan')
                ->where('created_by', $user->nip)
                ->count();
            $jumlahSKToday = DB::table('suratkeluar')
                ->where('jenis', 'biasa')
                ->where('status', 'digunakan')
                ->where('created_by', $user->nip)
                ->where('tanggalPengesahan', $today)
                ->count();
            $jumlahSA = DB::table('suratkeluar')
                ->where('jenis', 'antidatir')
                ->where('status', 'digunakan')
                ->where('created_by', $user->nip)
                ->count();
            $jumlahSAToday = $suratAntidatir
                ->where('created_by', $user->nip)
                ->where('updated_at', $formattedDate)
                ->count();
        } else {
            $jumlahSM = DB::table('suratmasuk')->count();
            $jumlahSK = DB::table('suratkeluar')
                ->where('jenis', 'biasa')
                ->where('status', 'digunakan')
                ->count();
            $jumlahSKToday = DB::table('suratkeluar')
                ->where('jenis', 'biasa')
                ->where('status', 'digunakan')
                ->where('tanggalPengesahan', $today)
                ->count();
            $jumlahSA = DB::table('suratkeluar')
                ->where('jenis', 'antidatir')
                ->where('status', 'digunakan')
                ->count();
            $jumlahSAToday = $suratAntidatir
                ->where('updated_at', $formattedDate)
                ->count();
        }

        // Ambil data jumlah surat masuk
        // Ambil nip user yang login
        $userNIP = $user->nip;
        // Cek kondisi admin
        if ($user->role_id === 1) {
            $jumlahSuratMasuk = DB::table('suratMasuk')->get();
        }
        // Cek kondisi selain admin
        else {
            $jumlahSuratMasuk = DB::table('suratMasuk')
                ->where('ditujukan_kepada', $userNIP)
                ->orWhere(function ($query) use ($userNIP) {
                    $query->whereIn('suratMasuk.id', function ($subquery) use ($userNIP) {
                        $subquery->select('id_surat')
                            ->from('disposisi')
                            ->where('nip_penerima', $userNIP);
                    });
                })
                ->get();
        }
        return view('index2')->with([
            'jumlahSM' => $jumlahSM,
            'jumlahSK' => $jumlahSK,
            'SKToday' => $jumlahSKToday,
            'jumlahSA' => $jumlahSA,
            'SAToday' => $jumlahSAToday,
            'date' => now(),
            'user' => $user,
            'suratKeluar' => $suratKeluar,
            'jumlahSuratMasuk' => $jumlahSuratMasuk,
        ]);
    }
}
