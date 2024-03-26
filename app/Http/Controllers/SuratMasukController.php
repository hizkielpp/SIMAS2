<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

use Illuminate\Http\Request;

class SuratMasukController extends Controller
{
    // Fungsi menampilkan halaman surat masuk start
    public function index()
    {
        // Ambil data user authorized
        $user = session()->get('user');

        // Cek kondisi admin dapat melihat semua surat
        if ($user->role_id === 1) {
            $suratMasuk = DB::table('suratMasuk')
                ->join('users', 'suratMasuk.ditujukan_kepada', '=', 'users.nip')
                ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')
                ->select('suratMasuk.*', 'users.name', 'jabatans.nama_jabatan')
                ->orderByDesc('created_at')
                ->get();
        }
        // Cek kondisi selain admin dapat melihat surat yang ditujukan kepadanya
        // Atau terdapat disposisi yang ditujukan kepadanya
        else {
            // Ambil nip user
            $userNIP = $user->nip;

            $suratMasuk = DB::table('suratMasuk')
                ->join('users', 'suratMasuk.ditujukan_kepada', '=', 'users.nip')
                ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')
                ->select('suratMasuk.*', 'users.name', 'jabatans.nama_jabatan')
                ->where('ditujukan_kepada', $userNIP)
                ->orWhere(function ($query) use ($userNIP) {
                    $query->whereIn('suratMasuk.id', function ($subquery) use ($userNIP) {
                        $subquery->select('id_surat')
                            ->from('disposisi')
                            ->where('nip_penerima', $userNIP);
                    });
                })
                ->orWhere(function ($query) use ($userNIP) {
                    $query->whereIn('suratMasuk.id', function ($subquery) use ($userNIP) {
                        $subquery->select('id_surat')
                            ->from('tembusan')
                            ->where('nip_tembusan', $userNIP);
                    });
                })
                ->orderByDesc('created_at')
                ->get();
        }

        // Ambil data ditujukan kepada pimpinan
        $ditujukanKepada = DB::table('users')
            ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')
            ->select('users.*', 'jabatans.nama_jabatan')
            ->whereNotIn('nama_jabatan', ['Tenaga Kependidikan'])
            ->get();

        // Ambil data sifat surat 
        $sifat = DB::table('sifat')->get();

        // Ambil data kode hal surat 
        $hal = DB::table('hal')->get();

        return view('surat-masuk.surat-masuk')->with([
            'user' => $user,
            'suratMasuk' => $suratMasuk,
            'sifat' => $sifat,
            'hal' => $hal,
            'ditujukanKepada' => $ditujukanKepada,
        ]);
    }
    // Fungsi menampilkan halaman surat masuk end

    // Mengambil data surat masuk untuk admin start
    protected function getSuratMasukForAdmin($start, $end)
    {
        $query = DB::table('suratMasuk')
            ->join('users', 'suratMasuk.ditujukan_kepada', '=', 'users.nip')
            ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')
            ->select('suratMasuk.*', 'users.name', 'jabatans.nama_jabatan')
            ->orderByDesc('created_at');

        if ($start && $end) {
            $query->whereBetween('tanggalPengajuan', [date('Y-m-d', $start), date('Y-m-d', $end)]);
        }

        return $query;
    }
    // Mengambil data surat masuk untuk admin end

    // Mengambil data surat masuk untuk user start
    protected function getSuratMasukForUser($user, $start, $end)
    {
        // Ambil nip user
        $userNIP = $user->nip;

        // Cek surat yang ditampilkan untuk sekretaris
        // Sekretaris dekan
        if ($user->bagian === 'Sekretaris Dekan') {
            $jabatan = DB::table('users')
                ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')
                ->where('nama_jabatan', 'Dekan')->first();
            $userNIP = $jabatan->nip;
        }
        // Sekretaris wakil dekan I
        elseif ($user->bagian === 'Sekretaris Wakil Dekan I') {
            $jabatan = DB::table('users')
                ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')
                ->where('nama_jabatan', 'Wakil Dekan I')->first();
            $userNIP = $jabatan->nip;
        }
        // Sekretaris wakil dekan II
        elseif ($user->bagian === 'Sekretaris Wakil Dekan II') {
            $jabatan = DB::table('users')
                ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')
                ->where('nama_jabatan', 'Wakil Dekan II')->first();
            $userNIP = $jabatan->nip;
        }

        $query = DB::table('suratMasuk')
            ->join('users', 'suratMasuk.ditujukan_kepada', '=', 'users.nip')
            ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')
            ->select('suratMasuk.*', 'users.name', 'jabatans.nama_jabatan')
            ->where('ditujukan_kepada', $userNIP);

        if ($start && $end) {
            $query->whereBetween('tanggalPengajuan', [date('Y-m-d', $start), date('Y-m-d', $end)]);
        }

        $query->orWhere(function ($query) use ($userNIP) {
            $query->whereIn('suratMasuk.id', function ($subquery) use ($userNIP) {
                $subquery->select('id_surat')
                    ->from('disposisi')
                    ->where('nip_penerima', $userNIP);
            });
        })
            ->orWhere(function ($query) use ($userNIP) {
                $query->whereIn('suratMasuk.id', function ($subquery) use ($userNIP) {
                    $subquery->select('id_surat')
                        ->from('tembusan')
                        ->where('nip_tembusan', $userNIP);
                });
            })
            ->orderByDesc('created_at');

        return $query;
    }
    // Mengambil data surat masuk untuk user end

    // Fungsi untuk menerapkan pencarian start
    protected function applySearch($query, $searchValue)
    {
        $searchQuery = $query;

        if (!empty($searchValue)) {
            $searchQuery = $query->where(function ($searchQuery) use ($searchValue) {
                $searchQuery->where('nomorSurat', 'like', '%' . $searchValue . '%')
                    ->orWhere('asalSurat', 'like', '%' . $searchValue . '%')
                    ->orWhere('lampiran', 'like', '%' . $searchValue . '%')
                    ->orWhere('perihal', 'like', '%' . $searchValue . '%')
                    ->orWhere('status_disposisi', 'like', '%' . $searchValue . '%')
                    ->orWhere('kodeHal', 'like', '%' . $searchValue . '%')
                    ->orWhere('name', 'like', '%' . $searchValue . '%')
                    ->orWhere('nama_jabatan', 'like', '%' . $searchValue . '%');
            });
        }

        return $searchQuery;
    }
    // Fungsi untuk menerapkan pencarian start

    // Fungsi server side datatables start
    public function getSuratMasuk(Request $request)
    {
        // Ambil data user yang login
        $user = session()->get('user');

        // Tentukan batas data per halamana
        $perPage = $request->input('length');
        $page = $request->input('start') / $perPage + 1;

        // Ambil value filter tanggal
        $start = strtotime($request->input('mulai'));
        $end = strtotime($request->input('selesai'));

        // Cek kondisi dengan role user yang login
        if ($user->role_id === 1) {
            $query = $this->getSuratMasukForAdmin($start, $end);
        } else {
            $query = $this->getSuratMasukForUser($user, $start, $end);
        }

        // Terapkan pencarian jika ada
        $searchValue = $request->input('search.value');
        $query = $this->applySearch($query, $searchValue);

        $filteredData = $query->count();
        $query->skip(($page - 1) * $perPage)->take($perPage);
        $suratMasuk = $query->get();
        $totalData = DB::table('suratmasuk')->count(); // Total jumlah data keseluruhan

        return response()->json([
            'data' => $suratMasuk,
            'recordsTotal' => $totalData,
            'recordsFiltered' => $filteredData,
        ]);
    }
    // Fungsi server side datatables end

    // Menampilkan detail surat start
    public function show(Request $request)
    {
        // Ambil data user
        $user = session()->get('user');

        // Ambil data jabatan user
        $jabatanUser = DB::table('jabatans')
            ->where('id', $user->id_jabatan)
            ->first()->nama_jabatan;

        // Ambil data surat sesuai id
        $surat = DB::table('suratMasuk')
            ->where('suratMasuk.id', $request->id)
            ->join('sifat', 'suratMasuk.sifatSurat', '=', 'sifat.kode')
            ->join('users', 'suratMasuk.ditujukan_kepada', '=', 'users.nip')
            ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')
            ->select('suratMasuk.*', 'sifat.nama as sifat_surat', 'users.name', 'jabatans.nama_jabatan')
            ->first();

        // Ambil data disposisi sesuai surat
        $disposisis = DB::table('disposisi')
            ->where('id_surat', $request->id)
            // Ambil data penerima disposisi
            ->join('users', 'disposisi.nip_penerima', '=', 'users.nip')
            ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')

            // Ambil data pengirim disposisi
            ->leftJoin('users as user_pengirim', 'disposisi.nip_pengirim', '=', 'user_pengirim.nip')
            ->leftJoin('jabatans as jabatan_pengirim', 'user_pengirim.id_jabatan', '=', 'jabatan_pengirim.id')

            ->join('tindak_lanjut', 'disposisi.id_tindak_lanjut', '=', 'tindak_lanjut.id')
            ->select('disposisi.*', 'users.name as nama_penerima', 'jabatans.nama_jabatan as jabatan_penerima', 'user_pengirim.name as nama_pengirim', 'jabatan_pengirim.nama_jabatan as jabatan_pengirim', 'tindak_lanjut.deskripsi',)
            ->get();

        // Ambil data yang menyelesaikan disposisi/disposisi terakhir
        $disposisiTerakhir = DB::table('disposisi')
            ->where('id_surat', $request->id)
            ->join('users', 'disposisi.nip_penerima', '=', 'users.nip')
            ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')
            ->orderByDesc('disposisi.created_at')
            ->first();

        // Atur format waktu lokal bahasa indonesia
        App::setLocale('id');
        // Tanggal disposisi
        foreach ($disposisis as $key) {
            $carbonTanggal = Carbon::parse($key->tanggal_disposisi);
            $formatTanggal = $carbonTanggal->isoFormat('DD MMMM YYYY HH:mm:ss');
            $key->tanggal_disposisi = $formatTanggal;

            // Tanggal surat diterima / created_at
            $carbonTanggalCreatedAt = Carbon::parse($key->created_at);
            $formatTanggalCreatedAt = $carbonTanggalCreatedAt->isoFormat('DD MMMM YYYY HH:mm:ss');
            $key->created_at = $formatTanggalCreatedAt;
        }
        // Tanggal surat diterima / created_at
        $suratCreatedAt = Carbon::parse($surat->created_at);
        $formatTanggal = $suratCreatedAt->isoFormat('dddd, DD MMMM YYYY HH:mm:ss');
        $surat->created_at = $formatTanggal;
        // Tanggal penyelesaian disposisi
        $tanggalPenyelesaianDisposisi = Carbon::parse($surat->tanggal_penyelesaian_disposisi);
        $tanggalPenyelesaianDisposisiFormatted = $tanggalPenyelesaianDisposisi->isoFormat('dddd, DD MMMM YYYY HH:mm:ss');
        $surat->tanggal_penyelesaian_disposisi = $tanggalPenyelesaianDisposisiFormatted;

        // Ambil data tindak lanjut
        $tindakLanjuts = DB::table('tindak_lanjut')->get();

        // Ambil data jabatan keseluruhan
        $jabatans = DB::table('jabatans')->get();

        // Mencari index jabatan user yang login dalam array
        $indexJabatanUser = $jabatans->search(function ($jabatan) use ($jabatanUser) {
            return $jabatan->nama_jabatan == $jabatanUser;
        });

        // Mengambil jabatan setelah jabatan user yang login
        $jabatanDapatDipilih = $jabatans->slice($indexJabatanUser + 1)->pluck('nama_jabatan', 'id')->toArray();

        // Mengambil NIP yang sudah mendapatkan disposisi
        $nipsYangSudahDisposisi = DB::table('riwayat_disposisi')
            ->where('id_surat', $request->id)
            ->where('pengirim_nip', $user->nip) // Ganti dengan NIP user yang login
            ->pluck('penerima_nip')
            ->toArray();

        // Menghilangkan NIP yang sudah mendapatkan disposisi dari jabatanDapatDipilih
        $jabatanDapatDipilih = collect($jabatanDapatDipilih)->reject(function ($jabatan, $nip) use ($nipsYangSudahDisposisi) {
            return in_array($nip, $nipsYangSudahDisposisi);
        })->toArray();

        // Mengambil data users dengan join ke tabel jabatans
        // Manager kebawah hanya dapat dispo ke bawahannya
        if (!in_array($jabatanUser, ['Dekan', 'Wakil Dekan I', 'Wakil Dekan II'])) {
            $usersWithJabatan = DB::table('users')
                ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')
                ->whereIn('jabatans.id', array_keys($jabatanDapatDipilih))
                ->select('users.*', 'jabatans.nama_jabatan')
                ->get();
        }
        // Dekan/wakil dekan 1/wakil dekan 2 dapat melakukan dispo lebih dari sekali
        else {
            $usersWithJabatan =  DB::table('users')
                ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')
                ->whereNotIn('jabatans.id', [$user->id_jabatan])
                ->select('users.*', 'jabatans.nama_jabatan')
                ->get();
        }

        // Cek kondisi user hanya dapat melakukan disposisi 1 kali
        $userTelahDispo = DB::table('riwayat_disposisi')
            ->where('pengirim_nip', $user->nip)
            ->where('id_surat', $request->id)
            ->exists();

        // Ambil data tembusan
        $tembusans = DB::table('tembusan')
            ->where('id_surat', $surat->id)
            ->join('users', 'tembusan.nip_tembusan', '=', 'users.nip')
            ->join('jabatans', 'users.id_jabatan', '=', 'jabatans.id')
            ->get();

        return view('surat-masuk.detail', compact('surat', 'user', 'disposisis', 'usersWithJabatan', 'tindakLanjuts', 'userTelahDispo', 'disposisiTerakhir', 'tembusans'));
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

        $suratMasukId = DB::table('suratmasuk')->insertGetId($validatedData);

        if ($request->has('tembusan')) {
            foreach ($request->input('tembusan') as $nip) {
                DB::table('tembusan')->insert([
                    'id_surat' => $suratMasukId,
                    'nip_tembusan' => $nip,
                    'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                    'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
                ]);
            }
        }

        return back()->with('success', 'Data surat masuk berhasil ditambahkan');
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

        // Ambil data user
        $user = session()->get('user');

        // Validasi inputan
        $validated = $request->validate([
            'nip_pengirim' => 'required',
            'nip_penerima' => 'required',
            'tanggal_disposisi' => 'required|date',
            'id_tindak_lanjut' => 'required',
            'keterangan' => 'required',
        ], [
            'nip_penerima.required' => 'Penerima disposisi wajib diisi!',
            'id_tindak_lanjut.required' => 'Tindak lanjut wajib diisi!',
        ]);

        // Sesuaikan tanggal jquery datepicker dengan format database 
        $tanggalDisposisi = Carbon::parse($request->input('tanggal_disposisi'))->format('Y-m-d');
        $waktuSaatSubmit = Carbon::now();
        $waktuFormatted = $waktuSaatSubmit->format('H:i:s');
        $tanggalWaktuDisposisi = $tanggalDisposisi . ' ' . $waktuFormatted;

        $validated['tanggal_disposisi'] = $tanggalWaktuDisposisi;
        $validated['id_surat'] = $request->id;
        $validated['created_at'] = Carbon::now();
        $validated['updated_at'] = Carbon::now();

        // Memastikan surat belum didisposisikan sebelumnya
        DB::table('disposisi')->insert($validated);

        // Tambahkan data ke riwayat disposisi
        DB::table('riwayat_disposisi')->insert([
            'pengirim_nip' => $user->nip,
            'penerima_nip' => $request->input('nip_penerima'),
            'id_surat' => $request->id,
            'created_at' => now(),
        ]);

        if ($surat->status_disposisi == 'Belum Diproses') {
            // Update status disposisi surat
            DB::table('suratMasuk')->where('id', $request->id)->update(['status_disposisi' => 'Diproses']);
        }
        return redirect()->back()->with('success', 'Surat berhasil didisposisikan!');
    }
    // Menambahkan disposisi surat end

    // Menyelesaikan disposisi surat start
    public function disposisiEnd(Request $request)
    {
        // Mendapatkan data surat berdasarkan ID
        DB::table('suratMasuk')->where('id', $request->id)->update([
            'status_disposisi' => 'Selesai',
            'tanggal_penyelesaian_disposisi' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('success', 'Disposisi surat telah selesai!');
    }
    // Menyelesaikan disposisi surat end

    // Handle tandai disposisi telah dibaca start
    public function disposisiOpened(Request $request)
    {
        $disposisi = DB::table('disposisi')
            ->where('id', $request->id)
            ->update(['isOpened' => 1]);

        return response()->json([
            'success' => 'success',
        ]);
    }
    // Handle tandai disposisi telah dibaca end

}
