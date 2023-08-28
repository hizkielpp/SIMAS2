<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;
use Illuminate\Support\Facades\Log;


class Surat extends Controller
{
    /* ---------------------------------------------- */
    /*               Surat masuk start                */
    /* ---------------------------------------------- */
    // Fungsi menampilkan halaman surat masuk start
    public function indexSM()
    {
        $user = session()->get('user');
        if (isset($_GET['start']) && isset($_GET['end'])) {
            $start = strtotime($_GET['start']);
            $end = strtotime($_GET['end']);

            // Kondisi untuk admin dan pimpinan dapat melihat semua surat
            if ($user->role_id == 1 || $user->role_id == 3) {
                $suratMasuk = DB::table('suratmasuk')
                    ->where('tanggalPengajuan', '>=', date('Y-m-d', $start))
                    ->where('tanggalPengajuan', '<=', date('Y-m-d', $end))
                    ->join('users', 'suratmasuk.created_by', '=', 'users.nip')
                    ->join('tujuan', 'suratmasuk.tujuanSurat', '=', 'tujuan.kode')->select('suratmasuk.*', 'users.name as name', 'users.bagian as bagian', 'tujuan.nama as namaTujuan')
                    ->orderBy('nomorSurat', 'desc')->get();
            }
            // Kondisi untuk operator hanya dapat melihat suratnya sendiri
            else {
                $suratMasuk = DB::table('suratmasuk')
                    ->where('tanggalPengajuan', '>=', date('Y-m-d', $start))
                    ->where('tanggalPengajuan', '<=', date('Y-m-d', $end))
                    ->where('created_by', $user->nip)
                    ->join('users', 'suratmasuk.created_by', '=', 'users.nip')
                    ->join('tujuan', 'suratmasuk.tujuanSurat', '=', 'tujuan.kode')->select('suratmasuk.*', 'users.name as name', 'users.bagian as bagian', 'tujuan.nama as namaTujuan')
                    ->orderBy('nomorSurat', 'desc')->get();
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
        if ($suratMasuk) {
            return view('surat-masuk')->with(
                [
                    'user' => $user,
                    'suratMasuk' => $suratMasuk,
                    'sifat' => $sifat,
                    'hal' => $hal,
                    'tujuan' => $tujuan
                ]
            );
        } else {
            return view('suratMasuk')->with(['failed' => 'data surat masuk kosong', 'sifat' => $sifat, 'hal' => $hal]);
        }
    }
    // Fungsi menampilkan halaman surat masuk end

    // Fungsi menambahkan surat masuk start
    public function inputSM(Request $request)
    {
        // Validasi input laravel start
        $validatedData = $request->validate([
            'nomorSurat' => 'required|unique:suratmasuk,nomorSurat',
            'tujuanSurat' => 'required',
            'tanggalPengajuan' => 'required',
            'asalSurat' => 'required',
            'kodeHal' => 'required',
            'sifatSurat' => 'required',
            'lampiran' => 'required|mimes:docx,pdf|max:2048',
            'perihal' => 'required',
            'jumlahLampiran' => 'nullable',
        ], [
            'nomorSurat.unique' => 'Nomor surat telah digunakan. Silahkan gunakan nomor surat lain.',
            'lampiran.max' => 'Ukuran maksimal upload file 2 MB'
        ]);
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
        $request->lampiran->move(public_path('uploads'), $fileName);
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
        $request->validate([
            'lampiran' => 'mimes:docx,pdf|max:2048'
        ], [
            'lampiran.max' => 'Ukuran maksimal upload file 2 MB'
        ]);
        $surat = DB::table('suratmasuk')->where('id', $request->input('idSurat'))->first();
        $updatedValue = $request->except(['_token', 'idSurat']);
        if ($request->file('lampiran')) {
            $file = $request->file('lampiran');
            $fileName = $file->getClientOriginalName();
            $request->lampiran->move(public_path('uploads'), $fileName);
            $filePath = public_path('uploads') . "\\" . $surat->lampiran;
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
                ->where('id', $request->input('idSurat'))  // find your surat by id
                ->limit(1)  // optional - to ensure only one record is updated.
                ->update($updatedValue);
            return redirect()->route('suratMasuk')->with('success', 'Data surat masuk berhasil diubah');
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
        $surat = DB::table('suratmasuk')->where('id', $request->input('idSurat'))->first();
        $filePath = public_path('uploads') . "\\" . $surat->lampiran;
        $deleted = DB::table('suratmasuk')->where('id', $request->input('idSurat'))->delete();
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
            ->join('users', 'suratmasuk.created_by', '=', 'users.nip')->select('suratmasuk.*', 'users.name as name', 'users.bagian as bagian')->first();
        return response()->json($suratMasuk);
    }
    // Fungsi get spesific surat masuk end

    // Fungsi disposisi start
    public function disposisi(Request $request)
    {
        if (isset($_GET['id'])) {
            $surat = DB::table('suratmasuk')->join('hal', 'hal.kode', '=', 'suratmasuk.kodeHal')->where('suratmasuk.id', $_GET['id'])->first();
            // dd($surat);
            if ($surat) {
                return view('lembar-disposisi')->with('surat', $surat);
            } else {
                return redirect()->route('suratMasuk')->with('failed', 'gagal menampilkan lembar disposisi surat masuk');
            }
        } else {
            return response('Request tidak valid', 400)->header('Content-Type', 'text/plain');
        }
    }
    // Fungsi disposisi start
    /* ---------------------------------------------- */
    /*                 Surat masuk end                */
    /* ---------------------------------------------- */

    /* ---------------------------------------------- */
    /*               Surat keluar start               */
    /* ---------------------------------------------- */
    // Fungsi menampilkan halaman surat keluar start
    public function indexSK()
    {
        $user = session()->get('user');
        $unit = DB::table('unit')->get();
        if (isset($_GET['start']) && isset($_GET['end'])) {
            $start = strtotime($_GET['start']);
            $end = strtotime($_GET['end']);

            // Kondisi untuk admin dan pimpinan dapat melihat semua surat
            if ($user->role_id == 1 || $user->role_id == 3) {
                $suratKeluar = DB::table('suratkeluar')->where('jenis', 'biasa')->where('tanggalPengesahan', '>=', date('Y-m-d', $start) . " 00:00:00.0")->where('tanggalPengesahan', '<=', date('Y-m-d', $end) . " 23:59:59.9")->join('users', 'suratkeluar.created_by', '=', 'users.nip')->select('suratkeluar.*', 'users.name as name', 'users.bagian as bagian')->orderBy('nomorSurat', 'desc')->get();
            }
            // Kondisi untuk operator hanya dapat melihat suratnya sendiri
            else {
                $suratKeluar = DB::table('suratkeluar')->where('jenis', 'biasa')->where('tanggalPengesahan', '>=', date('Y-m-d', $start) . " 00:00:00.0")->where('tanggalPengesahan', '<=', date('Y-m-d', $end) . " 23:59:59.9")->where('created_by', $user->nip)->join('users', 'suratkeluar.created_by', '=', 'users.nip')->select('suratkeluar.*', 'users.name as name', 'users.bagian as bagian')->orderBy('nomorSurat', 'desc')->get();
            }
        } else {
            // Kondisi untuk admin dan pimpinan dapat melihat semua surat
            if ($user->role_id == 1 || $user->role_id == 3) {
                $suratKeluar = DB::table('suratkeluar')->where('jenis', 'biasa')->join('users', 'suratkeluar.created_by', '=', 'users.nip')->select('suratkeluar.*', 'users.name as name', 'users.bagian as bagian')->orderBy('nomorSurat', 'desc')->get();
            }
            // Kondisi untuk operator hanya dapat melihat suratnya sendiri
            else {
                $suratKeluar = DB::table('suratkeluar')->where('jenis', 'biasa')->where('created_by', $user->nip)->join('users', 'suratkeluar.created_by', '=', 'users.nip')->select('suratkeluar.*', 'users.name as name', 'users.bagian as bagian')->orderBy('nomorSurat', 'desc')->get();
            }
        }

        $sifat = DB::table('sifat')->get();
        $tujuan = DB::table('tujuan')->get();
        $hal = DB::table('hal')->get();
        if ($suratKeluar) {
            return view('surat-keluar')->with(['user' => $user, 'tujuan' => $tujuan, 'suratKeluar' => $suratKeluar, 'sifat' => $sifat, 'hal' => $hal, 'unit' => $unit]);
        } else {
            return view('surat-keluar')->with(['failed' => 'data surat keluar kosong', 'sifat' => $sifat, 'hal' => $hal]);
        }
    }
    // Fungsi menampilkan halaman surat keluar end

    // Fungsi menambah surat keluar start
    public function inputSK(Request $request)
    {
        $request->validate([
            'nomorSurat' => 'required',
            'kodeHal' => 'required',
            'kodeUnit' => 'required',
            'sifatSurat' => 'required',
            'disahkanOleh' => 'required',
            'tanggalPengesahan' => 'required',
            // 'jumlahLampiran' => 'nullable',
            'tujuanSurat' => 'required',
            // 'lampiran' => 'mimes:docx,pdf|max:1024'
        ]);
        // dd($request);
        //ambil nomor agenda
        $nomorAgenda = DB::table('suratkeluar')
            ->whereMonth('tanggalPengesahan', '=', date('m', strtotime(now())))
            ->max('nomorAgenda');
        if ($nomorAgenda == null) {
            $nomorAgenda = 1;
        } else {
            $nomorAgenda++;
        }
        $userId = $request->session()->get('user')->nip;
        $input = $request->except(['_token']);
        // $input['lampiran'] = $fileName;
        $input['created_by'] = $userId;
        $input['nomorAgenda'] = $nomorAgenda;
        $input['jenis'] = 'biasa';
        $input['status'] = 'digunakan';
        $input['created_at'] = now();
        $input['updated_at'] = now();
        $input['tanggalPengesahan'] = date('Y-m-d', strtotime($request->input('tanggalPengesahan')));

        try {
            $target = DB::table('suratkeluar')->where('nomorSurat', $input['nomorSurat'])->where('created_at', '>=', date('Y-m-01', strtotime($input['tanggalPengesahan'])))->where('created_at', '<=', date('Y-m-t', strtotime($input['tanggalPengesahan'])))->first();
            if ($target) {
                // return redirect()->route('suratKeluar')->with('registrasiFailed', 'Nomor surat telah digunakan. Silahkan ambil nomor surat lain.');
                return back()->withInput()->with('registrasiFailed', 'Nomor surat telah digunakan. Silahkan ambil nomor surat lain.');
            } else {
                DB::table('suratkeluar')->insert($input);
                return redirect()->route('suratKeluar')->with('success', 'Data surat keluar berhasil ditambahkan');
            }
        } catch (\Exception $e) {
            return $e;
            return redirect()->route('suratKeluar')->with('failed', 'Gagal menambahkan data surat keluar' . $e);
        }
    }
    // Fungsi menambah surat keluar end

    // Fungsi edit surat keluar & antidatir start
    public function editSK(Request $request)
    {
        // dd($request);
        $jenisSurat = $request->input('jenisSurat');
        $request->validate(['lampiran' => 'mimes:docx,pdf|max:2048'], ['lampiran.max' => 'Ukuran maksimal upload file 2 MB']);
        $surat = DB::table('suratkeluar')->where('id', $request->input('idSurat'))->first();
        $updatedValue = $request->except(['_token', 'idSurat', 'jenisSurat']);
        // dd($updatedValue);
        if ($request->file('lampiran')) {
            $file = $request->file('lampiran');
            $fileName = $file->getClientOriginalName();
            $request->lampiran->move(public_path('uploads'), $fileName);
            $filePath = public_path('uploads') . "\\" . $surat->lampiran;
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            $updatedValue['lampiran'] = $fileName;
        }
        if ($request->input('tanggalPengesahanE') == null) {
            // dd('tidak ada');
            unset($updatedValue['tanggalPengesahanE']);
        } else {
            $updatedValue['tanggalPengesahan'] = $request->input('tanggalPengesahanE');
            unset($updatedValue['tanggalPengesahanE']);
            $updatedValue['tanggalPengesahan'] = date('Y-m-d', strtotime($updatedValue['tanggalPengesahan']));
        }
        $updatedValue['updated_at'] = now();
        try {
            DB::table('suratkeluar')
                ->where('id', $request->input('idSurat'))  // find your surat by id
                ->limit(1)  // optional - to ensure only one record is updated.
                ->update($updatedValue);
            if ($jenisSurat == "biasa") {
                return redirect()->route('suratKeluar')->with('success', 'Data surat keluar berhasil diubah');
            } else if ($jenisSurat == 'antidatir') {
                return redirect()->route('suratAntidatir')->with('success', 'Data surat antidatir berhasil diubah');
            }
        } catch (\Exception $e) {
            return $e;
            if ($jenisSurat == "biasa") {
                return redirect()->route('suratKeluar')->with('failed', 'Gagal mengubah data surat keluar' . $e);
            } else if ($jenisSurat == 'antidatir') {
                return redirect()->route('suratAntidatir')->with('failed', 'Gagal mengubah data surat antidatir' . $e);
            }
        }
    }
    // Fungsi edit surat keluar & antidatir end

    // Fungsi menghapus surat keluar start
    public function deleteSK(Request $request)
    {
        $surat = DB::table('suratkeluar')->where('id', $request->input('idSurat'))->first();
        $filePath = public_path('uploads') . "\\" . $surat->lampiran;
        $deleted = DB::table('suratkeluar')->where('id', $request->input('idSurat'))->delete();
        if ($deleted == 0) {
            return response('Data gagal dihapus', 406);
        } else {
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            return response('Data berhasil dihapus', 200);
        }
    }
    // Fungsi menghapus surat keluar end

    // Fungsi get spesific surat keluar start
    public function getSK(Request $request)
    {
        $suratMasuk = DB::table('suratkeluar')->where('id', $request->id)->join('users', 'suratkeluar.created_by', '=', 'users.nip')->select('suratkeluar.*', 'users.name as name', 'users.bagian as bagian')->first();
        return response()->json($suratMasuk);
    }
    // Fungsi get spesific surat keluar end

    // Fungsi upload dokumen start
    public function uploadDokumen(Request $req)
    {
        $validatedData = $req->validate([
            'dokumen' => 'required',
            'lampiran' => 'required|mimes:docx,pdf|max:2048',
            'jumlahLampiran' => 'nullable',
        ], ['lampiran.max' => 'Ukuran maksimal upload file 2 MB']);

        // Set input start
        $file = $req->file('lampiran');
        $fileName = $file->getClientOriginalName();
        $req->lampiran->move(public_path('uploads'), $fileName);
        $validatedData['lampiran'] = $fileName;
        // Set input end

        try {
            DB::table('suratkeluar')->where('id', $req->dokumen)->update(['lampiran' => $fileName]);
            return back()->with('success', 'Upload arsip berhasil');
        } catch (\Exception $e) {
            return $e;
            // Validasi nomor surat dengan database
            if (DB::table('suratmasuk')->where('nomorSurat', $validatedData['nomorSurat'])) {
                return back()->with('failed', 'Nomor surat telah digunakan. Silahkan gunakan nomor surat lain.');
            }
        }
    }
    // Fungsi upload dokumen end
    /* ---------------------------------------------- */
    /*                Surat keluar end                */
    /* ---------------------------------------------- */

    /* ---------------------------------------------- */
    /*             Surat antidatir start              */
    /* ---------------------------------------------- */
    // Fungsi menampilkan halaman surat antidatir start
    public function indexSA()
    {
        $user = session()->get('user');
        $unit = DB::table('unit')->get();
        $tujuan = DB::table('tujuan')->get();
        if (isset($_GET['start']) && isset($_GET['end'])) {
            $start = strtotime($_GET['start']);
            $end = strtotime($_GET['end']);

            // Kondisi untuk admin dan pimpinan dapat melihat semua surat
            if ($user->role_id == 1 || $user->role_id == 3) {
                $suratAntidatir = DB::table('suratkeluar')->where('created_at', '>=', date('Y-m-d', $start) . " 00:00:00.0")->where('created_at', '<=', date('Y-m-d', $end) . " 23:59:59.9")->where('jenis', 'antidatir')->where('status', 'digunakan')->join('users', 'suratkeluar.created_by', '=', 'users.nip')->select('suratkeluar.*', 'users.name as name', 'users.bagian as bagian')->orderBy('nomorSurat', 'desc')->get();
            }
            // Kondisi untuk operator hanya dapat melihat suratnya sendiri
            else {
                $suratAntidatir = DB::table('suratkeluar')->where('created_at', '>=', date('Y-m-d', $start) . " 00:00:00.0")->where('created_at', '<=', date('Y-m-d', $end) . " 23:59:59.9")->where('jenis', 'antidatir')->where('status', 'digunakan')->where('created_by', $user->nip)->join('users', 'suratkeluar.created_by', '=', 'users.nip')->select('suratkeluar.*', 'users.name as name', 'users.bagian as bagian')->orderBy('nomorSurat', 'desc')->get();
            }
        } else {
            // Kondisi untuk admin dan pimpinan dapat melihat semua surat
            if ($user->role_id == 1 || $user->role_id == 3) {
                $suratAntidatir = DB::table('suratkeluar')->where('jenis', 'antidatir')->where('status', 'digunakan')->join('users', 'suratkeluar.created_by', '=', 'users.nip')->select('suratkeluar.*', 'users.name as name', 'users.bagian as bagian')->orderBy('nomorSurat', 'desc')->get();
            }
            // Kondisi untuk operator hanya dapat melihat suratnya sendiri
            else {
                $suratAntidatir = DB::table('suratkeluar')->where('jenis', 'antidatir')->where('status', 'digunakan')->where('created_by', $user->nip)->join('users', 'suratkeluar.created_by', '=', 'users.nip')->select('suratkeluar.*', 'users.name as name', 'users.bagian as bagian')->orderBy('nomorSurat', 'desc')->get();
            }
        }
        $sifat = DB::table('sifat')->get();
        $hal = DB::table('hal')->get();
        if ($suratAntidatir) {
            return view('surat-antidatir')->with(['user' => $user, 'suratAntidatir' => $suratAntidatir, 'sifat' => $sifat, 'hal' => $hal, 'unit' => $unit, 'tujuan' => $tujuan]);
        } else {
            return view('surat-antidatir')->with(['failed' => 'data surat keluar kosong', 'sifat' => $sifat, 'hal' => $hal]);
        }
    }
    // Fungsi menampilkan halaman surat antidatir end

    // Fungsi menambahkan surat antidatir start
    public function inputSA(Request $request)
    {
        $request->validate([
            'nomorSurat' => 'required',
            'kodeHal' => 'required',
            'kodeUnit' => 'required',
            'sifatSurat' => 'required',
            'disahkanOleh' => 'required',
            'tanggalPengesahan' => 'required',
            'tujuanSurat' => 'required',
        ]);

        // dd($request);
        //ambil nomor agenda
        $nomorAgenda = DB::table('suratkeluar')->max('nomorAgenda');
        if ($nomorAgenda == null) {
            $nomorAgenda = 1;
        } else {
            $nomorAgenda++;
        }
        $userId = $request->session()->get('user')->nip;
        $input = $request->except(['_token']);
        $input['created_by'] = $userId;
        $input['nomorAgenda'] = $nomorAgenda;
        $input['jenis'] = 'antidatir';
        $input['status'] = 'digunakan';
        $input['updated_at'] = now();
        $input['tanggalPengesahan'] = date('Y-m-d', strtotime($input['tanggalPengesahan']));
        $time = strtotime($input['tanggalPengesahan']);
        try {
            $target = DB::table('suratkeluar')->where('nomorSurat', $input['nomorSurat'])->where('created_at', '>=', date('Y-m-01', strtotime($input['tanggalPengesahan'])))->where('created_at', '<=', date('Y-m-t', strtotime($input['tanggalPengesahan'])))->where('status', 'belum')->first();
            if ($target) {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                DB::table('suratkeluar')
                    ->where('id', $target->id)  // find your surat by nomor surat
                    ->limit(1)  // optional - to ensure only one record is updated.
                    ->update($input);
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                return redirect()->route('suratAntidatir')->with('success', 'Data surat antidatir berhasil ditambahkan');
            } else {
                return back()->withInput()->with('registrasiFailed', 'Nomor surat telah digunakan. Silahkan ambil nomor surat lain.');
            }
        } catch (\Exception $e) {
            // dd($e);
            return redirect()->route('suratAntidatir')->with('failed', 'gagal menginput data surat antidatir' . $e);
        }
    }
    // Fungsi menambahkan surat antidatir end
    /* ---------------------------------------------- */
    /*              Surat antidatir end               */
    /* ---------------------------------------------- */

    /* ---------------------------------------------- */
    /*              Fungsi bersama start              */
    /* ---------------------------------------------- */
    // Fungsi refresh data start
    public function refreshDatatable(Request $request)
    {
        if (isset($_GET['jenis'])) {
            if ($_GET['jenis'] == 'suratmasuk') {
                $suratmasuk = DB::table('suratmasuk')->get();
                return response()->json($suratmasuk);
            } elseif ($_GET['jenis'] == 'suratkeluar') {
                $suratkeluar = DB::table('suratkeluar')->where('jenis', 'biasa')->get();
                return response()->json($suratkeluar);
            } elseif ($_GET['jenis'] == 'suratantidatir') {
                $suratantidatir = DB::table('suratkeluar')->where('jenis', 'antidatir')->where('status', 'digunakan')->get();
                return response()->json($suratantidatir);
            }
        } else {
            return response('Request tidak valid', 400)->header('Content-Type', 'text/plain');
        }
    }
    // Fungsi refresh data end

    // Fungsi download tata naskah start
    public function downloadNaskah()
    {
        $filePath = public_path("Salinan Peraturan Rektor Nomor 5 tahun 2022 tentang TND.pdf");
        $headers = ['Content-Type: application/pdf'];
        $fileName = "Salinan Peraturan Rektor Nomor 5 tahun 2022 tentang TND.pdf";
        return response()->download($filePath, $fileName, $headers);
    }
    // Fungsi download tata naskah end

    // Fungsi ambil nomor surat keluar & antidatir start
    public function ambilNomor(Request $request)
    {
        // Inisialisasi batas 1 bulan
        $start = now()->startOfMonth()->toDateString();
        $end = now()->endOfMonth()->toDateString();

        // Surat keluar
        if ($request->input('jenis') == "biasa") {

            // Ambil nomor surat terakhir
            $suratKeluar = DB::table('suratkeluar')->where('created_at', '>=', $start)->where('created_at', '<=', $end)->max('nomorSurat');
            return response($suratKeluar + 1, 200)->header('Content-Type', 'text/plain');

            // Surat antidatir
        } elseif (($request->input('jenis') == "antidatir") and ($request->input('tanggalPengesahan'))) {
            $suratKeluar = DB::table('suratKeluar')->where('created_at', '>=', date('Y-m-d', strtotime($request->input('tanggalPengesahan'))) . " 00:00:00.0")->where('created_at', '<=', date('Y-m-d', strtotime($request->input('tanggalPengesahan'))) . " 23:59:59.9")->where('jenis', 'antidatir')->where('status', 'belum')->min('nomorSurat');
            if ($suratKeluar == 0) {
                return response('Nomor antidatir tidak tersedia', 404)->header('Content-Type', 'text/plain');
            } else {
                return response($suratKeluar, 200)->header('Content-Type', 'text/plain');
            }
        } else {
            return response('Request tidak valid', 401)->header('Content-Type', 'text/plain');
        }
    }
    // Fungsi ambil nomor surat keluar & antidatir end
    /* ---------------------------------------------- */
    /*               Fungsi bersama end               */
    /* ---------------------------------------------- */

    public function cekTersedia(Request $request)
    {
        if (isset($_GET['sumber'])) {
            if ($request->input('sumber') == 'keluar') {
                if ($request->input('jenis') == "biasa") {
                    $surat = DB::table('suratkeluar')->where('nomorSurat', '=', $request->id)->first();
                } else if ($request->input('jenis') == "antidatir") {
                    $surat = DB::table('suratkeluar')->where('nomorSurat', '=', $request->id)->where('status', 'digunakan')->first();
                }
            }
            if ($surat) {
                return response('Nomor sudah digunakan, silahkan ambil nomor lagi', 201)->header('Content-Type', 'text/plain');
            } else {
                return response('Nomor tersedia', 200)->header('Content-Type', 'text/plain');
            }
        } else {
            return response('Request tidak valid', 400)->header('Content-Type', 'text/plain');
        }
    }
}
