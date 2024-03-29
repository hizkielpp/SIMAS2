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
        // Admin start
        DB::table('users')->insert([
            "role_id" => 1,
            "name" => "Pak Mul",
            "bagian" => "Dekanat",
            "password" => Hash::make("password"),
            "email" => "pakmul@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '111',
        ]);
        // Admin end

        // Pimpinan start
        DB::table('users')->insert([
            "role_id" => 3,
            "name" => "Bu Agnes",
            "bagian" => "Dekanat",
            "password" => Hash::make("password"),
            "email" => "buagnes@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '222',
        ]);
        DB::table('users')->insert([
            "role_id" => 3,
            "name" => "Bu Lulut",
            "bagian" => "Dekanat",
            "password" => Hash::make("password"),
            "email" => "bululut@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '333',
        ]);
        // Pimpinan end

        // Operator start
        DB::table('users')->insert([
            "role_id" => 2,
            "name" => "Tendik 1",
            "bagian" => "Bagian 1",
            "password" => Hash::make("password"),
            "email" => "tendik1@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '1',
        ]);
        DB::table('users')->insert([
            "role_id" => 2,
            "name" => "Tendik 2",
            "bagian" => "Bagian 2",
            "password" => Hash::make("password"),
            "email" => "tendik2@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '2',
        ]);
        DB::table('users')->insert([
            "role_id" => 2,
            "name" => "Tendik 3",
            "bagian" => "Bagian 3",
            "password" => Hash::make("password"),
            "email" => "tendik3@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '3',
        ]);
        DB::table('users')->insert([
            "role_id" => 2,
            "name" => "Tendik 4",
            "bagian" => "Bagian 4",
            "password" => Hash::make("password"),
            "email" => "tendik4@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '4',
        ]);
        DB::table('users')->insert([
            "role_id" => 2,
            "name" => "Tendik 5",
            "bagian" => "Bagian 5",
            "password" => Hash::make("password"),
            "email" => "tendik5@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '5',
        ]);
        DB::table('users')->insert([
            "role_id" => 2,
            "name" => "Tendik 6",
            "bagian" => "Bagian 6",
            "password" => Hash::make("password"),
            "email" => "tendik6@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '6',
        ]);
        DB::table('users')->insert([
            "role_id" => 2,
            "name" => "Tendik 7",
            "bagian" => "Bagian 7",
            "password" => Hash::make("password"),
            "email" => "tendik7@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '7',
        ]);
        DB::table('users')->insert([
            "role_id" => 2,
            "name" => "Tendik 8",
            "bagian" => "Bagian 8",
            "password" => Hash::make("password"),
            "email" => "tendik8@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '8',
        ]);
        DB::table('users')->insert([
            "role_id" => 2,
            "name" => "Tendik 9",
            "bagian" => "Bagian 9",
            "password" => Hash::make("password"),
            "email" => "tendik9@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '9',
        ]);
        DB::table('users')->insert([
            "role_id" => 2,
            "name" => "Tendik 10",
            "bagian" => "Bagian 10",
            "password" => Hash::make("password"),
            "email" => "tendik10@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '10',
        ]);
        DB::table('users')->insert([
            "role_id" => 2,
            "name" => "Tendik 11",
            "bagian" => "Bagian 11",
            "password" => Hash::make("password"),
            "email" => "tendik11@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '11',
        ]);
        DB::table('users')->insert([
            "role_id" => 2,
            "name" => "Tendik 12",
            "bagian" => "Bagian 12",
            "password" => Hash::make("password"),
            "email" => "tendik12@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '12',
        ]);
        DB::table('users')->insert([
            "role_id" => 2,
            "name" => "Tendik 13",
            "bagian" => "Bagian 13",
            "password" => Hash::make("password"),
            "email" => "tendik13@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '13',
        ]);
        DB::table('users')->insert([
            "role_id" => 2,
            "name" => "Tendik 14",
            "bagian" => "Bagian 14",
            "password" => Hash::make("password"),
            "email" => "tendik14@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '14',
        ]);
        DB::table('users')->insert([
            "role_id" => 2,
            "name" => "Tendik 15",
            "bagian" => "Bagian 15",
            "password" => Hash::make("password"),
            "email" => "tendik15@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '15',
        ]);
        // Operator end
        // User seeder end

        // Unit seeder start
        $unit = [
            ["UN7.F4", "Dekan"],
            ["UN7.F4.1", "Wakil Dekan Akademik & Kemahasiswaan"],
            ["UN7.F4.2", "Wakil Dekan Sumber Daya"],
            // ["UN7.F4.3", "Ketua Senat"],
            // ["UN7.F4.3.1", "Sekretaris Senat"],
            ["UN7.F4.4", "Manager Tata Usaha"],
            ["UN7.F4.4.1", "Supervisor Akademik & Kemahasiswaan"],
            ["UN7.F4.4.2", "Supervisor Sumber Daya"],
            // ["UN7.F4.5", "Ketua Departemen"],
            // ["UN7.F4.6", "Ketua Program Studi"]
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
            ["AK", "Akademik"],
            ["AS", "Aset dan Sarana"],
            ["DK", "Dosen dan Tenaga Kependidikan"],
            ["HM", "Hubungan Masyarakat"],
            ["HK", "Hukum"],
            ["IN", "Inovasi"],
            ["KL", "Kelembagaan"],
            ["KM", "Kemahasiswaan"],
            ["KP", "Keputusan Kepegawaian"],
            ["UP", "Korespondensi Kepegawaian"],
            ["KS", "Kerja Sama"],
            ["RT", "Kerumahtanggaan"],
            ["TU", "Ketatausahaan"],
            ["KU", "Keuangan"],
            ["OT", "Organisasi dan Tata Laksana"],
            ["DL", "Pendidikan dan Pelatihan"],
            ["PP", "Penelitian"],
            ["PM", "Pengabdian kepada Masyarakat"],
            ["PW", "Pengawasan"],
            ["PR", "Perencanaan"],
            ["PK", "Perpustakaan"],
            ["SR", "Sertifikasi"],
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

        // Surat keluar seeder start
        $startNomorSurat = 1; // Nomor surat awal
        $jumlahSurat = 20; // Jumlah surat yang ingin Anda buat

        for ($i = 0; $i < $jumlahSurat; $i++) {
            // Generate nomor surat dengan menggabungkan nomor awal dengan indeks loop
            $nomorSurat = $startNomorSurat + $i;

            if ($i % 2 === 0) {
                # code...
                DB::table('suratkeluar')->insert([
                    'nomorSurat' => $nomorSurat, // Ganti sesuai format kode surat yang Anda inginkan
                    'kodeHal' => 'AK', // Ganti dengan pembuat surat yang sesuai
                    'sifatSurat' => 1, // Ganti dengan pembuat surat yang sesuai
                    'created_by' => 111, // Ganti dengan pembuat surat yang sesuai
                    'tanggalPengesahan' => '2023-10-03', // Ganti dengan pembuat surat yang sesuai
                    'kodeUnit' => 'UN7.F4', // Ganti dengan pembuat surat yang sesuai
                    'disahkanOleh' => 'Dekan', // Ganti dengan pembuat surat yang sesuai
                    'jenis' => 'biasa', // Ganti dengan pembuat surat yang sesuai
                    'status' => 'digunakan', // Ganti dengan pembuat surat yang sesuai
                    'tujuanSurat' => 'aaa', // Ganti dengan pembuat surat yang sesuai
                    'perihal' => 'tes', // Ganti dengan pembuat surat yang sesuai
                    'lampiran' => 'applsci-11-04267.pdf', // Ganti dengan pembuat surat yang sesuai
                ]);
            } else {
                DB::table('suratkeluar')->insert([
                    'nomorSurat' => $nomorSurat, // Ganti sesuai format kode surat yang Anda inginkan
                    'kodeHal' => 'PP', // Ganti dengan pembuat surat yang sesuai
                    'sifatSurat' => 1, // Ganti dengan pembuat surat yang sesuai
                    'created_by' => 111, // Ganti dengan pembuat surat yang sesuai
                    'tanggalPengesahan' => '2023-10-03', // Ganti dengan pembuat surat yang sesuai
                    'kodeUnit' => 'UN7.F4', // Ganti dengan pembuat surat yang sesuai
                    'disahkanOleh' => 'Dekan', // Ganti dengan pembuat surat yang sesuai
                    'jenis' => 'biasa', // Ganti dengan pembuat surat yang sesuai
                    'status' => 'digunakan', // Ganti dengan pembuat surat yang sesuai
                    'tujuanSurat' => 'bbb', // Ganti dengan pembuat surat yang sesuai
                    'perihal' => 'tes', // Ganti dengan pembuat surat yang sesuai
                    'lampiran' => 'applsci-11-04267.pdf', // Ganti dengan pembuat surat yang sesuai
                ]);
            }
        }

        // DB::table('suratkeluar')->insert([
        //     'nomorSurat' => 1, // Ganti sesuai format kode surat yang Anda inginkan
        //     'kodeHal' => 'AK', // Ganti dengan pembuat surat yang sesuai
        //     'sifatSurat' => 1, // Ganti dengan pembuat surat yang sesuai
        //     'created_by' => 111, // Ganti dengan pembuat surat yang sesuai
        //     'tanggalPengesahan' => '2023-10-03', // Ganti dengan pembuat surat yang sesuai
        //     'kodeUnit' => 'UN7.F4', // Ganti dengan pembuat surat yang sesuai
        //     'disahkanOleh' => 'Dekan', // Ganti dengan pembuat surat yang sesuai
        //     'jenis' => 'biasa', // Ganti dengan pembuat surat yang sesuai
        //     'status' => 'digunakan', // Ganti dengan pembuat surat yang sesuai
        //     'tujuanSurat' => 'aaa', // Ganti dengan pembuat surat yang sesuai
        //     'perihal' => 'tes', // Ganti dengan pembuat surat yang sesuai
        //     'created_at' => now(), // Ganti dengan pembuat surat yang sesuai
        //     'updated_at' => now(), // Ganti dengan pembuat surat yang sesuai
        // ]);
        // DB::table('suratkeluar')->insert([
        //     'nomorSurat' => 2, // Ganti sesuai format kode surat yang Anda inginkan
        //     'kodeHal' => 'AK', // Ganti dengan pembuat surat yang sesuai
        //     'sifatSurat' => 1, // Ganti dengan pembuat surat yang sesuai
        //     'created_by' => 111, // Ganti dengan pembuat surat yang sesuai
        //     'tanggalPengesahan' => '2023-10-03', // Ganti dengan pembuat surat yang sesuai
        //     'kodeUnit' => 'UN7.F4', // Ganti dengan pembuat surat yang sesuai
        //     'disahkanOleh' => 'Dekan', // Ganti dengan pembuat surat yang sesuai
        //     'jenis' => 'biasa', // Ganti dengan pembuat surat yang sesuai
        //     'status' => 'digunakan', // Ganti dengan pembuat surat yang sesuai
        //     'tujuanSurat' => 'bbb', // Ganti dengan pembuat surat yang sesuai
        //     'perihal' => 'tes', // Ganti dengan pembuat surat yang sesuai
        //     'created_at' => now(), // Ganti dengan pembuat surat yang sesuai
        //     'updated_at' => now(), // Ganti dengan pembuat surat yang sesuai
        // ]);
        // DB::table('suratkeluar')->insert([
        //     'nomorSurat' => 3, // Ganti sesuai format kode surat yang Anda inginkan
        //     'kodeHal' => 'AK', // Ganti dengan pembuat surat yang sesuai
        //     'sifatSurat' => 1, // Ganti dengan pembuat surat yang sesuai
        //     'created_by' => 111, // Ganti dengan pembuat surat yang sesuai
        //     'tanggalPengesahan' => '2023-10-03', // Ganti dengan pembuat surat yang sesuai
        //     'kodeUnit' => 'UN7.F4', // Ganti dengan pembuat surat yang sesuai
        //     'disahkanOleh' => 'Dekan', // Ganti dengan pembuat surat yang sesuai
        //     'jenis' => 'biasa', // Ganti dengan pembuat surat yang sesuai
        //     'status' => 'digunakan', // Ganti dengan pembuat surat yang sesuai
        //     'tujuanSurat' => 'ccc', // Ganti dengan pembuat surat yang sesuai
        //     'perihal' => 'tes', // Ganti dengan pembuat surat yang sesuai
        //     'created_at' => now(), // Ganti dengan pembuat surat yang sesuai
        //     'updated_at' => now(), // Ganti dengan pembuat surat yang sesuai
        // ]);

        // Surat keluar seeder end
    }
}
