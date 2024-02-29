<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class DisposisiController extends Controller
{
    // Menampilkan halaman daftar disposisi start
    public function index()
    {
        $user = session()->get('user');

        $disposisis = DB::table('disposisi')
            ->join('suratMasuk', 'disposisi.id_surat', '=', 'suratMasuk.id')
            ->join('users', 'disposisi.nip_penerima', '=', 'users.nip')
            ->select('disposisi.*', 'suratMasuk.*', 'users.name')
            ->get();
        return view('disposisi.disposisi', compact('user', 'disposisis'));
    }
    // Menampilkan halaman daftar disposisi end

    // Memproses surat dari admin untuk didisposisikan start
    public function teruskan(Request $request)
    {
        // Mendapatkan data surat berdasarkan ID
        $surat = DB::table('suratMasuk')->where('id', $request->input('id_surat'))->first();

        // Memastikan surat belum didisposisikan sebelumnya
        if ($surat->status_disposisi == 'Belum Diproses') {
            // Logika untuk proses disposisi ke pimpinan
            DB::table('disposisi')->insert([
                'id_surat' => $surat->id,
                'nip_penerima' => $request->input('nip_penerima'),
                'isi_disposisi' => 'Disposisi untuk dekan',
                'status_disposisi' => 'Baru',
                'tanggal_disposisi' => now(),
            ]);

            // Update status disposisi surat
            DB::table('suratMasuk')->where('id', $request->input('id_surat'))->update(['status_disposisi' => 'Diproses']);

            // Redirect atau berikan respons sukses
            return redirect()->back()->with('success', 'Surat berhasil diteruskan.');
        }
    }
    // Memproses surat dari admin untuk didisposisikan end

    // Menampilkan halaman daftar disposisi start
    public function store(Request $request)
    {
        return $request->nip_penerima;
        $user = session()->get('user');

        // $disposisis = Category::all();
        return view('disposisi.disposisi', compact('user'));
    }
    // Menampilkan halaman daftar disposisi end

}
