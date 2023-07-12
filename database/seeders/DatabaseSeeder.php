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
            "name" => "Pak Mul",
            "bagian" => "Dekanat",
            "password" => Hash::make("password"),
            "email" => "pakmul@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '1',
        ]);
        DB::table('users')->insert([
            "role_id" => 2,
            "name" => "Anton Wibowo",
            "bagian" => "KUI",
            "password" => Hash::make("password"),
            "email" => "antonwibowo@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '2',
        ]);
        DB::table('users')->insert([
            "role_id" => 3,
            "name" => "Bu Agnes",
            "bagian" => "Dekanat",
            "password" => Hash::make("password"),
            "email" => "buagnes@gmail.com",
            "created_at" => now(),
            "updated_at" => now(),
            "nip" => '3',
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
    }
}
