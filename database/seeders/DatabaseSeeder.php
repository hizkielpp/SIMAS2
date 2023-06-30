<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Initializing carbon
        $dateNow = Carbon::now();

        // Role seeder start
        $role = [
            ["1", "Admin"],
            ["2", "Operator"],
            ["3", "Pimpinan"]
        ];
        foreach ($role as $k => $v) {
            DB::table('role')->insert([
                "kode" => $v[0],
                "nama" => $v[1],
                "created_at" => now(),
                "updated_at" => now()
            ]);
        }
        // Role seeder start

        // User seeder start
        DB::table('users')->insert([
            "role_id" => 1,
            "name" => "Admin",
            "password" => Hash::make("password"),
            "email" => "admin@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '123',
        ]);
        DB::table('users')->insert([
            "role_id" => 2,
            "name" => "Operator",
            "password" => Hash::make("password"),
            "email" => "operator@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '1234',
        ]);
        // User seeder end

        // Unit seeder start
        $unit = [
            ["UN7.F4", "Dekan"],
            ["UN7.F4.1", "Wakil Dekan Akademik & Kemahasiswaan"],
            ["UN7.F4.2", "Wakil Dekan Sumber Daya"],
            ["UN7.F4.3", "Ketua Senat"],
            ["UN7.F4.3.1", "Sekretaris Senat"],
            ["UN7.F4.4", "Manager Tata Usaha"],
            ["UN7.F4.4.1", "Supervisor Akademik & Kemahasiswaan"],
            ["UN7.F4.4.2", "Supervisor Sumber Daya"],
            ["UN7.F4.5", "Ketua Departemen"],
            ["UN7.F4.6", "Ketua Program Studi"]
        ];
        foreach ($unit as $k => $v) {
            DB::table('unit')->insert([
                "kode" => $v[0],
                "nama" => $v[1],
                "created_at" => now(),
                "updated_at" => now()
            ]);
        }
        // Unit seeder end

        // Tujuan seeder start
        $tujuan = [
            ["DEAN", "Dekan Fakultas"],
            ["WDI", "Wakil Dekan I"],
            ["WDII", "Wakil Dekan II"],
            ["KTU", "Manager Bagian Tata Usaha"],
            ["SAK", "Supervisor Akademik & Kemahasiswaan"],
            ["SSD", "Supervisor Sumber Daya"],
            ["ATU", "Admin Tata Usaha"],
            ["Dep.Ked", "Departemen Kedokteran"],
            ["PPDS", "Departemen Kedokteran Spesialis"],
            ["D.Kep", "Departemen Ilmu Keperawatan"],
            ["DIG", "Departemen Ilmu Gizi"],
            ["AAK", "Admin Subbagian Akademik & Kemahasiswaan"],
            ["AKK", "Admin Subbagian Keuangan & Kepegawaian"],
            ["AUPA", "Admin Subbagian Umum & Pengelolaan Aset"],
            ["SNT", "Senat Fakultas"]
        ];
        foreach ($tujuan as $k => $v) {
            DB::table('tujuan')->insert([
                "kode" => $v[0],
                "nama" => $v[1],
                "created_at" => now(),
                "updated_at" => now()
            ]);
        }
        // Tujuan seeder end

        // Hal seeder start
        $hal = [
            ["AK", "Akreditasi"],
            ["BW", "Beasiswa"],
            ["DO", "Dokumentasi"],
            ["DK", "Dosen dan Tenaga Kependidikan"],
            ["EP", "Evaluasi Pendidikan"],
            ["HM", "Hubungan Masyarakat"],
            ["HK", "Hukum"],
            ["IN", "Inovasi"],
            ["KI", "Kalibrasi"],
            ["KL", "Kelembagaan"],
            ["KM", "Kemahasiswaan"],
            ["KP", "Kepegawaian"],
            ["KS", "Kerja Sama"],
            ["RT", "Kerumahtanggaan"],
            ["TU", "Ketatausahaan"],
            ["KU", "Keuangan"],
            ["KR", "Kurikulum"],
            ["MI", "Media Informasi"],
            ["MK", "Media Kreatif"],
            ["OT", "Organisasi dan Tata Laksana"],
            ["DL", "Pendidikan dan Pelatihan"],
            ["PP", "Penelitian dan Pengembangan"],
            ["PM", "Pengabdian kepada Masyarakat"],
            ["PW", "Pengawasan"],
            ["PG", "Pengembangan"],
            ["IL", "Penyetaraan Ijazah Luar Negeri"],
            ["PR", "Perencanaan"],
            ["PI", "Perizinan"],
            ["PL", "Perlengkapan"],
            ["PK", "Perpustakaan"],
            ["PB", "Publikasi Ilmiah"],
            ["SP", "Sarana Pendidikan"],
            ["RG", "Registrasi Pendidikan"],
            ["SR", "Sertifikasi"],
            ["SE", "Surat Edaran"],
            ["SK", "Surat Kuasa"],
            ["TI", "Teknologi Informasi"],
            ["PJ", "Penjaminan Mutu"],
            ["LL", "Lain-lain"]
        ];
        foreach ($hal as $k => $v) {
            DB::table('hal')->insert([
                "kode" => $v[0],
                "nama" => $v[1],
                "created_at" => now(),
                "updated_at" => now()
            ]);
        }
        // Hal seeder end

        // Sifat seeder start
        $sifat = [
            ["1", "Biasa"],
            ["2", "Penting"],
            ["3", "Segera"],
            ["4", "Rahasia"]
        ];
        foreach ($sifat as $k => $v) {
            DB::table('sifat')->insert([
                "kode" => $v[0],
                "nama" => $v[1],
                "created_at" => now(),
                "updated_at" => now()
            ]);
        }
        // Sifat seeder end

        // Surat masuk seeder start
        // for ($i = 1; $i <= 10; $i++) {
        //     DB::table('suratMasuk')->insert([
        //         "nomorAgenda" => $i,
        //         "nomorSurat" => $i . "/UN7.F4/2015/X",
        //         "kodeHal" => "KU",
        //         "tujuanSurat" => "DEAN",
        //         "sifatSurat" => "1",
        //         "tanggalPengajuan" => $dateNow->subDay(),
        //         "asalSurat" => "KETUA DEPARTEMEN ILMU GIZI",
        //         "jumlahLampiran" => 2,
        //         "created_by" => 1,
        //         "lampiran" => "default-lampiran.pdf",
        //         "perihal" => "Permohonan Kenaikan Gaji Pegawai Kontrak",
        //         "created_at" => now(),
        //         "updated_at" => now()
        //     ]);
        // }
        // Surat masuk seeder end

        // Surat keluar seeder start
        // for ($i = 1; $i <= 10; $i++) {
        //     DB::table('suratKeluar')->insert([
        //         "nomorAgenda" => $i,
        //         "nomorSurat" => $i,
        //         "kodeUnit" => "UN7.5.4.1",
        //         "kodeHal" => "KU",
        //         "sifatSurat" => "1",
        //         "status" => 'digunakan',
        //         "tanggalPengesahan" => $dateNow->subDay(),
        //         "tujuanSurat" => "RSND",
        //         "disahkanOleh" => "Dekan",
        //         "lampiran" => "default-lampiran.pdf",
        //         "jumlahLampiran" => 2,
        //         "perihal" => "Permohonan penelitian universitas",
        //         "created_at" => now(),
        //         "updated_at" => now(),
        //         "created_by" => 1,

        //     ]);
        // }
        // Surat keluar seeder end

        // Surat antidatir seeder start
        // $max = DB::table('suratKeluar')->max('nomorAgenda');
        // for ($i = 1; $i <= 20; $i++) {
        //     if ($i <= 10) {
        //         DB::table('suratKeluar')->insert([
        //             "nomorAgenda" => $max + $i,
        //             "kodeUnit" => "UN7.5.4.1",
        //             "nomorSurat" => $max + $i,
        //             "kodeHal" => "KU",
        //             "tujuanSurat" => "RSND",
        //             "sifatSurat" => "1",
        //             "tanggalPengesahan" => now(),
        //             "created_by" => 1,
        //             // "asalSurat"=> "KETUA DEPARTEMEN ILMU GIZI",
        //             "jumlahLampiran" => 2,
        //             "disahkanOleh" => "Dekan",
        //             "status" => "digunakan",
        //             "lampiran" => "oke.jpg",
        //             "perihal" => "Permohonan Kenaikan Gaji Pegawai Kontrak",
        //             "created_at" => now(),
        //             'jenis' => 'antidatir',
        //             "updated_at" => now()
        //         ]);
        //     } else {
        //         DB::table('suratKeluar')->insert([
        //             "nomorAgenda" => null,
        //             "kodeUnit" => "UN7.5.4.1",
        //             "nomorSurat" => $max + $i,
        //             "kodeHal" => "KU",
        //             "tujuanSurat" => "RSND",
        //             "disahkanOleh" => "Dekan",
        //             "sifatSurat" => "1",
        //             "tanggalPengesahan" => now(),
        //             // "asalSurat"=> "KETUA DEPARTEMEN ILMU GIZI",
        //             "jumlahLampiran" => 2,
        //             "created_by" => 1,
        //             "status" => "belum",
        //             "lampiran" => "oke.jpg",
        //             "perihal" => "Permohonan Kenaikan Gaji Pegawai Kontrak",
        //             "created_at" => now(),
        //             'jenis' => 'antidatir',
        //             "updated_at" => now()
        //         ]);
        //     }
        // }
        // Surat antidatir seeder end
    }
}
