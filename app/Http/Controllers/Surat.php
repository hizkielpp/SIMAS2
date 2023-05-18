<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use PDF;


class Surat extends Controller
{
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
    public function getSM(Request $request)
    {
        $suratMasuk = DB::table('suratmasuk')->where('id', $request->id)->first();
        return response()->json($suratMasuk);
    }
    public function getSK(Request $request)
    {
        $suratMasuk = DB::table('suratkeluar')->where('id', $request->id)->first();
        return response()->json($suratMasuk);
    }
    public function downloadNaskah()
    {
        $filePath = public_path("TATA_NASKAH_DINAS.pdf");
        $headers = ['Content-Type: application/pdf'];
        $fileName = time() . '.pdf';
        return response()->download($filePath, $fileName, $headers);
    }
    public function inputSA(Request $request)
    {
        // dd($request);
        $request->validate([
            'nomorSurat' => 'required',
            'kodeHal' => 'required',
            'kodeUnit' => 'required',
            'sifatSurat' => 'required',
            'disahkanOleh' => 'required',
            'tanggalPengesahan' => 'required',
            'jumlahLampiran' => 'required|integer',
            'tujuanSurat' => 'required',
            'lampiran' => 'required|mimes:docx,pdf|max:2000'
        ]);
        //ambil nomor agenda
        $nomorAgenda = DB::table('suratkeluar')->max('nomorAgenda');
        if ($nomorAgenda == null) {
            $nomorAgenda = 1;
        } else {
            $nomorAgenda++;
        }
        $userId = Auth::id();
        $input = $request->except(['_token']);
        $input['created_by'] = $userId;
        $input['nomorAgenda'] = $nomorAgenda;
        $input['jenis'] = 'antidatir';
        $input['status'] = 'digunakan';
        $input['updated_at'] = now();
        $time = strtotime($input['tanggalPengesahan']);
        // dd(date('Y-m-01', strtotime($input['tanggalPengesahan'])).date('Y-m-t', strtotime($input['tanggalPengesahan'])));
        try {
            $target = DB::table('suratkeluar')->where('nomorSurat', $input['nomorSurat'])->where('created_at', '>=', date('Y-m-01', strtotime($input['tanggalPengesahan'])))->where('created_at', '<=', date('Y-m-t', strtotime($input['tanggalPengesahan'])))->where('status', 'belum')->first();
            // dd($target);
            if ($target) {
                $file = $request->file('lampiran');
                $fileName = time() . '.' . $file->getClientOriginalName();
                $request->lampiran->move(public_path('uploads'), $fileName);
                $input['lampiran'] = $fileName;
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
                DB::table('suratkeluar')
                    ->where('id', $target->id)  // find your surat by nomor surat
                    ->limit(1)  // optional - to ensure only one record is updated.
                    ->update($input);
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
                return redirect()->route('suratAntidatir')->with('success', 'berhasil menginput data surat antidatir');
            } else {
                return redirect()->route('suratAntidatir')->with('failed', 'nomor surat telah dipakai, silahkan input ulang');
            }
        } catch (\Exception $e) {
            return redirect()->route('suratAntidatir')->with('failed', 'gagal menginput data surat antidatir' . $e);
        }
    }
    public function inputSM(Request $request)
    {
        // dd($request);
        $request->validate([
            'nomorSurat' => 'required',
            'kodeHal' => 'required',
            'sifatSurat' => 'required',
            'tanggalPengajuan' => 'required',
            'jumlahLampiran' => 'required',
            'asalSurat' => 'required',
            'tujuanSurat' => 'required',
            'lampiran' => 'required|mimes:docx,pdf|max:2000'
        ]);
        //validasi nomor surat
        $result = DB::table('suratmasuk')->where('nomorSurat', $request->input('nomorSurat'))->first();
        if ($result) {
            return redirect()->route('suratMasuk')->with('failed', 'Nomor surat telah digunakan. Silahkan gunakan nomor surat lain.');
        }

        //ambil nomor agenda
        $nomorAgenda = DB::table('suratmasuk')->max('nomorAgenda');
        if ($nomorAgenda == null) {
            $nomorAgenda = 1;
        } else {
            $nomorAgenda++;
        }

        $userId = Auth::id();
        $file = $request->file('lampiran');
        $fileName = time();
        $request->lampiran->move(public_path('uploads'), $fileName);


        try {
            DB::table('suratmasuk')->insert([
                'created_by' => $userId,
                'nomorAgenda' => $nomorAgenda,
                'nomorSurat' => $request->input('nomorSurat'),
                'kodeHal' => $request->input('kodeHal'),
                'sifatSurat' => $request->input('sifatSurat'),
                'tanggalPengajuan' => date('Y-m-d', strtotime($request->input('tanggalPengajuan'))),
                'asalSurat' => $request->input('asalSurat'),
                'jumlahLampiran' => $request->input('jumlahLampiran'),
                'lampiran' => $fileName,
                'perihal' => $request->input('perihal'),
                'created_at' => now(),
                'updated_at' => now(),
                'tujuanSurat' => $request->input('tujuanSurat')
            ]);
            return redirect()->route('suratMasuk')->with('success', 'Data surat masuk berhasil ditambahkan');
        } catch (\Exception $e) {
            return $e;
            return redirect()->route('suratMasuk')->with('failed', 'gagal menginput data surat masuk' . $e);
        }
    }
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
    public function editSM(Request $request)
    {
        $surat = DB::table('suratmasuk')->where('id', $request->input('idSurat'))->first();
        $updatedValue = $request->except(['_token', 'idSurat']);
        if ($request->file('lampiran')) {
            $file = $request->file('lampiran');
            $fileName = time() . '.' . $file->getClientOriginalName();
            $request->lampiran->move(public_path('uploads'), $fileName);
            $filePath = public_path('uploads') . "\\" . $surat->lampiran;
            if (File::exists($filePath)) {
                File::delete($filePath);
            }
            $updatedValue['lampiran'] = $fileName;
        }
        $userId = Auth::id();
        $updatedValue['tanggalPengajuan'] = date('Y-m-d', strtotime($request->input('tanggalPengajuan')));
        try {

            DB::table('suratmasuk')
                ->where('id', $request->input('idSurat'))  // find your surat by id
                ->limit(1)  // optional - to ensure only one record is updated.
                ->update($updatedValue);
            return redirect()->route('suratMasuk')->with('success', 'Data surat masuk berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->route('suratMasuk')->with('failed', 'Gagal mengubah data surat masuk' . $e);
        }
    }
    public function editSK(Request $request)
    {
        $jenisSurat = $request->input('jenisSurat');
        $surat = DB::table('suratkeluar')->where('id', $request->input('idSurat'))->first();
        $updatedValue = $request->except(['_token', 'idSurat', 'jenisSurat']);
        // dd($updatedValue);
        if ($request->file('lampiran')) {
            $file = $request->file('lampiran');
            $fileName = time() . '.' . $file->getClientOriginalName();
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
            if ($jenisSurat == "biasa") {
                return redirect()->route('suratKeluar')->with('failed', 'Gagal mengubah data surat keluar' . $e);
            } else if ($jenisSurat == 'antidatir') {
                return redirect()->route('suratAntidatir')->with('failed', 'Gagal mengubah data surat antidatir' . $e);
            }
        }
    }
    public function indexSM(Request $request)
    {
        $user = Auth::user();
        if (isset($_GET['start']) && isset($_GET['end'])) {
            $start = strtotime($_GET['start']);
            $end = strtotime($_GET['end']);

            if ($user->role == 2) {
                $suratMasuk = DB::table('suratmasuk')->where('tanggalPengajuan', '>=', date('Y-m-d', $start))->where('tanggalPengajuan', '<=', date('Y-m-d', $end))->where('created_by', $user->id)->orderBy('created_at', 'desc')->get();
            } else {
                $suratMasuk = DB::table('suratmasuk')->where('tanggalPengajuan', '>=', date('Y-m-d', $start))->where('tanggalPengajuan', '<=', date('Y-m-d', $end))->orderBy('created_at', 'desc')->get();
            }
        } else {
            //jika operator hanya menampilkan data miliknya
            if ($user->role == 2) {
                $suratMasuk = DB::table('suratmasuk')->where('created_by', $user->id)->orderBy('created_at', 'desc')->get();
            } else {
                $suratMasuk = DB::table('suratmasuk')->orderBy('created_at', 'desc')->get();
            }
        }
        $tujuan = DB::table('tujuan')->get();
        $sifat = DB::table('sifat')->get();
        $hal = DB::table('hal')->get();
        // dd($suratMasuk);
        if ($suratMasuk) {
            return view('surat-masuk')->with(['user' => $user, 'suratMasuk' => $suratMasuk, 'sifat' => $sifat, 'hal' => $hal, 'tujuan' => $tujuan]);
        } else {
            return view('suratMasuk')->with(['failed' => 'data surat masuk kosong', 'sifat' => $sifat, 'hal' => $hal]);
        }
    }
    public function indexSA(Request $request)
    {
        $user = Auth::user();
        $unit = DB::table('unit')->get();
        $tujuan = DB::table('tujuan')->get();
        if (isset($_GET['start']) && isset($_GET['end'])) {
            $start = $_GET['start'];
            $end = $_GET['end'];
            if ($user->role == 2) {
                $suratAntidatir = DB::table('suratkeluar')->where('created_at', '>=', $start . " 00:00:00.0")->where('created_at', '<=', $end . " 23:59:59.9")->where('jenis', 'antidatir')->where('status', 'digunakan')->where('created_by', $user->id)->get();
            } else {
                $suratAntidatir = DB::table('suratkeluar')->where('created_at', '>=', $start . " 00:00:00.0")->where('created_at', '<=', $end . " 23:59:59.9")->where('jenis', 'antidatir')->where('status', 'digunakan')->get();
            }
        } else {
            if ($user->role == 2) {
                $suratAntidatir = DB::table('suratkeluar')->where('jenis', 'antidatir')->where('status', 'digunakan')->where('created_by', $user->id)->get();
            } else {
                $suratAntidatir = DB::table('suratkeluar')->where('jenis', 'antidatir')->where('status', 'digunakan')->get();
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
    public function indexSK(Request $request)
    {
        $user = Auth::user();
        $unit = DB::table('unit')->get();
        if (isset($_GET['start']) && isset($_GET['end'])) {
            $start = $_GET['start'];
            $end = $_GET['end'];
            if ($user->role == 2) {
                $suratKeluar = DB::table('suratkeluar')->where('jenis', 'biasa')->where('created_at', '>=', $start . " 00:00:00.0")->where('created_at', '<=', $end . " 23:59:59.9")->where('created_by', $user->id)->get();
            } else {
                $suratKeluar = DB::table('suratkeluar')->where('jenis', 'biasa')->where('created_at', '>=', $start . " 00:00:00.0")->where('created_at', '<=', $end . " 23:59:59.9")->get();
            }
        } else {
            if ($user->role == 2) {
                $suratKeluar = DB::table('suratkeluar')->where('jenis', 'biasa')->where('created_by', $user->id)->get();
            } else {
                $suratKeluar = DB::table('suratkeluar')->where('jenis', 'biasa')->get();
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
    public function inputSK(Request $request)
    {
        $request->validate([
            'nomorSurat' => 'required',
            'kodeHal' => 'required',
            'kodeUnit' => 'required',
            'sifatSurat' => 'required',
            'disahkanOleh' => 'required',
            'tanggalPengesahan' => 'required',
            'jumlahLampiran' => 'required|integer',
            'tujuanSurat' => 'required',
            'lampiran' => 'required|mimes:docx,pdf|max:2000'
        ]);
        //ambil nomor agenda
        $nomorAgenda = DB::table('suratkeluar')->max('nomorAgenda');
        if ($nomorAgenda == null) {
            $nomorAgenda = 1;
        } else {
            $nomorAgenda++;
        }
        $userId = Auth::id();
        $file = $request->file('lampiran');
        $fileName = time() . '.' . $file->getClientOriginalName();
        $request->lampiran->move(public_path('uploads'), $fileName);
        $input = $request->except(['_token']);
        $input['lampiran'] = $fileName;
        $input['created_by'] = $userId;
        $input['nomorAgenda'] = $nomorAgenda;
        $input['jenis'] = 'biasa';
        $input['status'] = 'digunakan';
        $input['created_at'] = now();
        $input['updated_at'] = now();
        // dd($input);
        try {
            DB::table('suratkeluar')->insert($input);
            return redirect()->route('suratKeluar')->with('success', 'berhasil menginput data surat keluar');
        } catch (\Exception $e) {
            return redirect()->route('suratKeluar')->with('failed', 'gagal menginput data surat keluar' . $e);
        }
    }

    public function ambilNomor(Request $request)
    {
        $start = now()->startOfMonth()->toDateString();
        $end = now()->endOfMonth()->toDateString();
        if ($request->input('jenis') == "biasa") {
            $suratKeluar = DB::table('suratKeluar')->where('created_at', '>=', $start)->where('created_at', '<=', $end)->max('nomorSurat');
            return response($suratKeluar + 1, 200)->header('Content-Type', 'text/plain');
        } elseif (($request->input('jenis') == "antidatir") and ($request->input('tanggalPengesahan'))) {
            $suratKeluar = DB::table('suratKeluar')->where('created_at', '>=', $request->input('tanggalPengesahan') . " 00:00:00.0")->where('created_at', '<=', $request->input('tanggalPengesahan') . " 23:59:59.9")->where('jenis', 'antidatir')->where('status', 'belum')->min('nomorSurat');
            if ($suratKeluar == 0) {
                return response('Antidatir tidak tersedia', 404)->header('Content-Type', 'text/plain');
            } else {
                return response($suratKeluar, 200)->header('Content-Type', 'text/plain');
            }
        } else {
            return response('Request tidak valid', 401)->header('Content-Type', 'text/plain');
        }
    }
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
    public function upload(Request $request)
    {
        // dd($request->file);
        $fileName = time() . '.' . $request->file->extension();
        $request->file->move(public_path('uploads'), $fileName);
        return response($fileName, 200);
    }
}
