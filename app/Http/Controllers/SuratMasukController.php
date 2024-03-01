<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    // Fungsi menampilkan halaman surat masuk start
    public function index()
    {
        // Ambil data user authorized
        $user = session()->get('user');

        // Ambil data ditujukan kepada pimpinan
        $ditujukanKepada = DB::table('users')
            ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')
            ->select('users.*', 'jabatans.nama_jabatan')
            ->whereNotIn('nama_jabatan', ['Tenaga Kependidikan'])
            ->get();

        // Ambil data semua surat masuk
        $surat = DB::table('suratMasuk')
            ->join('users', 'suratMasuk.ditujukan_kepada', '=', 'users.nip')
            ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')
            ->select('suratMasuk.*', 'users.name', 'jabatans.nama_jabatan')
            ->orderByDesc('created_at');

        // Cek kondisi admin dapat melihat semua surat
        if ($user->role_id == 1) {
            $surat = $surat->get();
        }
        // Cek jika belum ada surat yang didisposisikan
        else if (count(DB::table('disposisi')->get()) === 0) {
            $surat = $surat->where('ditujukan_kepada', $user->nip)
                ->get();
        }
        // Cek jika surat telah didisposisikan
        else {
            $surat = DB::table('disposisi')
                ->where('nip_penerima', $user->nip)
                ->join('suratMasuk', 'disposisi.id_surat', '=', 'suratMasuk.id')
                ->join('users', 'suratMasuk.ditujukan_kepada', '=', 'users.nip')
                ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')
                ->select('disposisi.*', 'suratMasuk.*', 'users.name', 'jabatans.nama_jabatan')
                ->get();
        }

        // Ambil data sifat surat 
        $sifat = DB::table('sifat')->get();

        // Ambil data kode hal surat 
        $hal = DB::table('hal')->get();

        return view('surat-masuk.surat-masuk')->with([
            'user' => $user,
            'suratMasuk' => $surat,
            'sifat' => $sifat,
            'hal' => $hal,
            'ditujukanKepada' => $ditujukanKepada,
        ]);
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
            ->join('users', 'suratMasuk.ditujukan_kepada', '=', 'users.nip')
            ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')
            ->select('suratMasuk.*', 'sifat.nama as sifat_surat', 'users.name', 'jabatans.nama_jabatan')
            ->first();

        // Ambil data disposisi sesuai surat
        $disposisis = DB::table('disposisi')
            ->where('id_surat', $request->id)
            ->join('users', 'disposisi.nip_penerima', '=', 'users.nip')
            ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')
            ->join('tindak_lanjut', 'disposisi.id_tindak_lanjut', '=', 'tindak_lanjut.id')
            ->select('disposisi.*', 'users.name as tujuan', 'tindak_lanjut.deskripsi', 'jabatans.nama_jabatan as nama_jabatan')
            ->get();

        // Cek kondisi disposisi hanya kebawah
        $ditujukanKepada = DB::table('users')
            ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')
            ->select('users.*', 'jabatans.nama_jabatan');
        if ($surat->nama_jabatan === 'Dekan') {
            $ditujukanKepada->whereNotIn('nama_jabatan', ['Dekan']);
        } elseif ($surat->nama_jabatan === 'Wakil Dekan I') {
            $ditujukanKepada->whereNotIn('nama_jabatan', ['Dekan', 'Wakil Dekan I']);
        } elseif ($surat->nama_jabatan === 'Wakil Dekan II') {
            $ditujukanKepada->whereNotIn('nama_jabatan', ['Dekan', 'Wakil Dekan I', 'Wakil Dekan II']);
        } elseif ($surat->nama_jabatan === 'Manager Bagian Tata Usaha') {
            $ditujukanKepada->whereNotIn('nama_jabatan', ['Dekan', 'Wakil Dekan I', 'Wakil Dekan II', 'Manager Bagian Tata Usaha']);
        } elseif ($surat->nama_jabatan === 'Supervisor Akademik & Kemahasiswaan') {
            $ditujukanKepada->whereNotIn('nama_jabatan', ['Dekan', 'Wakil Dekan I', 'Wakil Dekan II', 'Manager Bagian Tata Usaha', 'Supervisor Akademik & Kemahasiswaan']);
        } elseif ($surat->nama_jabatan === 'Supervisor Sumber Daya') {
            $ditujukanKepada->whereNotIn('nama_jabatan', ['Dekan', 'Wakil Dekan I', 'Wakil Dekan II', 'Manager Bagian Tata Usaha', 'Supervisor Akademik & Kemahasiswaan', 'Supervisor Sumber Daya']);
        }
        $ditujukanKepada = $ditujukanKepada->get();

        // Ambil data tindak lanjut
        $tindakLanjuts = DB::table('tindak_lanjut')->get();

        return view('surat-masuk.detail', compact('surat', 'user', 'disposisis', 'ditujukanKepada', 'tindakLanjuts'));
    }
    // Menampilkan detail surat end

    // Fungsi menambahkan surat masuk start
    public function inputSM(Request $request)
    {
        // Validasi input laravel start
        $validatedData = $request->validate(
            [
                'nomorSurat' => 'required|unique:suratmasuk,nomorSurat',
                'tanggalPengajuan' => 'required',
                'asalSurat' => 'required',
                'kodeHal' => 'required',
                'sifatSurat' => 'required',
                'lampiran' => 'required|mimes:docx,pdf|max:2048',
                'perihal' => 'required',
                'jumlahLampiran' => 'nullable',
                'ditujukan_kepada' => 'required',
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

    // Menambahkan disposisi surat start
    public function disposisiStore(Request $request)
    {
        // Mendapatkan data surat berdasarkan ID
        $surat = DB::table('suratMasuk')->where('id', $request->id)->first();

        // Validasi inputan
        $validated = $request->validate([
            'nip_penerima' => 'required',
            'tanggal_disposisi' => 'required|date',
            'id_tindak_lanjut' => 'required',
            'isi_disposisi' => 'required',
        ]);

        $validated['id_surat'] = $request->id;
        $validated['tanggal_disposisi'] = Carbon::parse($request->input('tanggal_disposisi'))->format('Y-m-d H:i:s');

        // Memastikan surat belum didisposisikan sebelumnya
        DB::table('disposisi')->insert($validated);
        if ($surat->status_disposisi == 'Belum Diproses') {
            // Update status disposisi surat
            DB::table('suratMasuk')->where('id', $request->id)->update(['status_disposisi' => 'Diproses']);
        }
        return redirect()->back()->with('success', 'Surat berhasil didisposisikan!');
    }
    // Menambahkan disposisi surat end


}
