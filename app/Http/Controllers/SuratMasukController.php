<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    // Fungsi menampilkan halaman surat masuk start
    public function index()
    {
        $user = session()->get('user');
        $jabatanUser = DB::table('jabatans')
            ->where('id', $user->id_jabatan)
            ->first();
        if (isset($_GET['start']) && isset($_GET['end'])) {
            $start = strtotime($_GET['start']);
            $end = strtotime($_GET['end']);

            // Kondisi untuk admin dan pimpinan dapat melihat semua surat
            if ($user->role_id == 1 || $user->role_id == 3) {
                $suratMasuk = DB::table('suratmasuk')
                    ->where('tanggalPengajuan', '>=', date('Y-m-d', $start))
                    ->where('tanggalPengajuan', '<=', date('Y-m-d', $end))
                    ->join('users', 'suratmasuk.created_by', '=', 'users.nip')
                    ->join('tujuan', 'suratmasuk.tujuanSurat', '=', 'tujuan.kode')
                    ->select('suratmasuk.*', 'users.name as name', 'users.bagian as bagian', 'tujuan.nama as namaTujuan')
                    ->orderBy('nomorSurat', 'desc')
                    ->get();
            }
            // Kondisi untuk operator hanya dapat melihat suratnya sendiri
            else {
                $suratMasuk = DB::table('suratmasuk')
                    ->where('tanggalPengajuan', '>=', date('Y-m-d', $start))
                    ->where('tanggalPengajuan', '<=', date('Y-m-d', $end))
                    ->where('created_by', $user->nip)
                    ->join('users', 'suratmasuk.created_by', '=', 'users.nip')
                    ->join('tujuan', 'suratmasuk.tujuanSurat', '=', 'tujuan.kode')
                    ->select('suratmasuk.*', 'users.name as name', 'users.bagian as bagian', 'tujuan.nama as namaTujuan')
                    ->orderBy('nomorSurat', 'desc')
                    ->get();
            }
        } else {
            // Kondisi untuk admin dan pimpinan dapat melihat semua surat
            if ($user->role_id == 1 || $user->role_id == 3) {
                $suratMasuk = DB::table('suratmasuk')
                    ->orderBy('nomorSurat', 'desc')
                    ->join('users', 'suratmasuk.created_by', '=', 'users.nip')
                    ->join('tujuan', 'suratmasuk.tujuanSurat', '=', 'tujuan.kode')
                    ->select('suratmasuk.*', 'users.name as name', 'users.bagian as bagian', 'tujuan.nama as namaTujuan')
                    ->get();
            }
            // Kondisi untuk operator hanya dapat melihat suratnya sendiri
            else {
                $suratMasuk = DB::table('suratmasuk')
                    ->where('created_by', $user->nip)
                    ->orderBy('nomorSurat', 'desc')
                    ->join('users', 'suratmasuk.created_by', '=', 'users.nip')
                    ->join('tujuan', 'suratmasuk.tujuanSurat', '=', 'tujuan.kode')
                    ->select('suratmasuk.*', 'users.name as name', 'users.bagian as bagian', 'tujuan.nama as namaTujuan')
                    ->get();
            }
        }
        $tujuan = DB::table('tujuan')->get();
        $sifat = DB::table('sifat')->get();
        $hal = DB::table('hal')->get();
        $tujuanTeruskan = DB::table('users')->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')
            ->select('users.*', 'jabatans.nama_jabatan')
            ->whereIn('nama_jabatan', ['Dekan', 'Wakil Dekan I', 'Wakil Dekan II', 'Manager Bagian Tata Usaha'])
            ->get();
        if ($suratMasuk) {
            return view('surat-masuk.surat-masuk')->with([
                'user' => $user,
                'suratMasuk' => $suratMasuk,
                'sifat' => $sifat,
                'hal' => $hal,
                'tujuan' => $tujuan,
                'tujuanTeruskan' => $tujuanTeruskan,
                'jabatanUser' => $jabatanUser,
            ]);
        } else {
            return view('surat-masuk.surat-masuk')->with(['failed' => 'data surat masuk kosong', 'sifat' => $sifat, 'hal' => $hal]);
        }
    }
    // Fungsi menampilkan halaman surat masuk end

    // Menampilkan detail surat start
    public function show(Request $request)
    {
        // Ambil data user
        $user = session()->get('user');

        // Ambil data surat sesuai id
        $surat = DB::table('suratMasuk')->where('suratMasuk.id', $request->id)
            ->join('sifat', 'suratMasuk.sifatSurat', '=', 'sifat.kode')
            ->first();

        // Ambil data disposisi sesuai surat
        $disposisis = DB::table('disposisi')
            ->where('id_surat', $request->id)
            ->join('users', 'disposisi.nip_penerima', '=', 'users.nip')
            ->select('disposisi.*', 'users.name as tujuan')
            ->get();

        // Ambil data penerima disposisi start
        $penerimaDisposisi = DB::table('users')
            ->get();
        // Ambil data penerima disposisi end
        return view('surat-masuk.detail', compact('surat', 'user', 'disposisis', 'penerimaDisposisi'));
    }
    // Menampilkan detail surat end

    // Fungsi menambahkan surat masuk start
    public function inputSM(Request $request)
    {
        // Validasi input laravel start
        $validatedData = $request->validate(
            [
                'nomorSurat' => 'required|unique:suratmasuk,nomorSurat',
                'tujuanSurat' => 'required',
                'tanggalPengajuan' => 'required',
                'asalSurat' => 'required',
                'kodeHal' => 'required',
                'sifatSurat' => 'required',
                'lampiran' => 'required|mimes:docx,pdf|max:2048',
                'perihal' => 'required',
                'jumlahLampiran' => 'nullable',
            ],
            [
                'nomorSurat.unique' => 'Nomor surat telah digunakan. Silahkan gunakan nomor surat lain.',
                'lampiran.max' => 'Ukuran maksimal upload file 2 MB',
            ],
        );
        // Validasi input laravel end

        // Set nomor agenda start
        $nomorAgenda = DB::table('suratmasuk')->max('nomorAgenda');
        if ($nomorAgenda == null) {
            $nomorAgenda = 1;
        } else {
            $nomorAgenda++;
        }
        // Set nomor agenda start

        // Set input start
        $userId = $request->session()->get('user')->nip;
        $file = $request->file('lampiran');
        $fileName = $file->getClientOriginalName();
        $request->lampiran->move('public/uploads', $fileName);
        $validatedData['created_by'] = $userId;
        $validatedData['nomorAgenda'] = $nomorAgenda;
        $validatedData['tanggalPengajuan'] = date('Y-m-d', strtotime($request->input('tanggalPengajuan')));
        $validatedData['lampiran'] = $fileName;
        $validatedData['created_at'] = now();
        $validatedData['updated_at'] = now();
        // Set input end

        try {
            DB::table('suratmasuk')->insert($validatedData);
            return back()->with('success', 'Data surat masuk berhasil ditambahkan');
        } catch (\Exception $e) {
            return $e;
        }
    }
    // Fungsi menambahkan surat masuk end

    // Fungsi edit surat masuk start
    public function editSM(Request $request)
    {
        $request->validate(
            [
                'lampiran' => 'mimes:docx,pdf|max:2048',
            ],
            [
                'lampiran.max' => 'Ukuran maksimal upload file 2 MB',
            ],
        );
        $surat = DB::table('suratmasuk')
            ->where('id', $request->input('idSurat'))
            ->first();
        $updatedValue = $request->except(['_token', 'idSurat']);
        if ($request->file('lampiran')) {
            $file = $request->file('lampiran');
            $timestamp = now()->timestamp;
            $fileName = $timestamp . '-' . $file->getClientOriginalName();
            $request->lampiran->move('public/uploads', $fileName);
            $filePath = 'public/uploads' . '/' . $surat->lampiran;
            // $folder = 'uploads';
            // $fileBefore = $surat->lampiran;
            // $filePath = public_path($folder . '/' . $fileBefore);
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            $updatedValue['lampiran'] = $fileName;
        }
        $userId = $request->session()->get('user')->nip;
        $updatedValue['tanggalPengajuan'] = date('Y-m-d', strtotime($request->input('tanggalPengajuan')));
        try {
            DB::table('suratmasuk')
                ->where('id', $request->input('idSurat')) // find your surat by id
                ->limit(1) // optional - to ensure only one record is updated.
                ->update($updatedValue);
            return redirect()
                ->route('surat-masuk.index')
                ->with('success', 'Data surat masuk berhasil diubah');
        } catch (\Exception $e) {
            if (DB::table('suratmasuk')->where('nomorSurat', $request->input('nomorSurat'))) {
                return back()->with('editFailed', 'Nomor surat telah digunakan. Silahkan gunakan nomor surat lain.');
            }
            return $e;
            // Validasi nomor surat dengan database
            // return redirect()->route('suratMasuk')->with('failed', 'Gagal mengubah data surat masuk' . $e);
        }
    }
    // Fungsi edit surat masuk start

    // Fungsi menghapus surat masuk start
    public function deleteSM(Request $request)
    {
        $surat = DB::table('suratmasuk')
            ->where('id', $request->input('idSurat'))
            ->first();
        $filePath = 'public/uploads' . '/' . $surat->lampiran;
        $deleted = DB::table('suratmasuk')
            ->where('id', $request->input('idSurat'))
            ->delete();
        if ($deleted == 0) {
            return response('Data gagal dihapus', 406);
        } else {
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            return response('Data berhasil dihapus', 200);
        }
    }
    // Fungsi menghapus surat masuk end

    // Fungsi get spesific surat masuk start
    public function getSM(Request $request)
    {
        $suratMasuk = DB::table('suratmasuk')
            ->where('id', $request->id)
            ->join('users', 'suratmasuk.created_by', '=', 'users.nip')
            ->select('suratmasuk.*', 'users.name as name', 'users.bagian as bagian')
            ->first();
        return response()->json($suratMasuk);
    }
    // Fungsi get spesific surat masuk end

    // Fungsi disposisi start
    public function disposisi(Request $request)
    {
        if (isset($_GET['id'])) {
            $surat = DB::table('suratmasuk')
                ->join('hal', 'hal.kode', '=', 'suratmasuk.kodeHal')
                ->where('suratmasuk.id', $_GET['id'])
                ->first();
            // dd($surat);
            if ($surat) {
                return view('lembar-disposisi')->with('surat', $surat);
            } else {
                return redirect()
                    ->route('surat-masuk.index')
                    ->with('failed', 'gagal menampilkan lembar disposisi surat masuk');
            }
        } else {
            return response('Request tidak valid', 400)->header('Content-Type', 'text/plain');
        }
    }
    // Fungsi disposisi start
}
