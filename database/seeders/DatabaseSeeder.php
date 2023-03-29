<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $role = [["1","Super Admin"],
        ["2","Operator"],
        ["3","Pimpinan"]];
        foreach($role as $k => $v)
        {
            DB::table('role')->insert([
                "kode" => $v[0],
                "nama" => $v[1],
                "created_at" => now(),
                "updated_at" => now()
            ]);            
        }
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $faker = Faker::create();
        DB::table('users')->insert([
            "role"=>1,
            "name" => "Pak Admin Mul",
            "password" => Hash::make("password"),
            "email" => "admin@gmail.com",
            "created_at" => now(),
            "updated_at" => now()
        ]);
        DB::table('users')->insert([
            "role"=>2,
            "name" => "Pak Kadep1",
            "password" => Hash::make("password"),
            "email" => "kadep1@gmail.com",
            "created_at" => now(),
            "updated_at" => now()
        ]);
        DB::table('users')->insert([
            "role"=>2,
            "name" => "Pak Kadep2",
            "password" => Hash::make("password"),
            "email" => "kadep2@gmail.com",
            "created_at" => now(),
            "updated_at" => now()
        ]);
        DB::table('users')->insert([
            "role"=>3,
            "name" => "Pak Pemimpin",
            "password" => Hash::make("password"),
            "email" => "pemimpin@gmail.com",
            "created_at" => now(),
            "updated_at" => now()
        ]);
        //seeder untuk tabel Unit
        $unit = [["UN7.5.4.1","Ketua Senat"],
        ["UN7.5.4.1.1","Sekretaris Senat"],
        ["UN7.5.4.2","Dekan"],
        ["UN7.5.4.2.1","Wakil Dekan Akademik & Kemahasiswaan"],
        ["UN7.5.4.2.2","Wakil Dekan Sumberdaya"],
        ["UN7.5.4.3","Kepala Bagian Tata Usaha"],
        ["UN7.5.4.3.1","Kepala Subbagian Akademik & Kemahasiswaan"],
        ["UN7.5.4.3.2","Kepala Subbagian Keuangan & Kepegawaian"],
        ["UN7.5.4.3.3","Kepala Subbagian Umum & Pengelolaan Aset"],
        ["UN7.5.4.4","Ketua Departemen"]];
        foreach($unit as $k => $v)
        {
            DB::table('unit')->insert([
                "kode" => $v[0],
                "nama" => $v[1],
                "created_at" => now(),
                "updated_at" => now()
            ]);            
        }
        //seeder untuk tabel Tujuan
        $tujuan = [["DEAN","Dekan Fakultas"],
        ["WDI","Wakil Dekan I"],
        ["WDII","Wakil Dekan II"],
        ["KTU","Manager Bagian Tata Usaha"],
        ["SAK","Supervisor Akademik & Kemahasiswaan"],
        ["SSD","Supervisor Sumber Daya"],
        ["ATU","Admin Tata Usaha"],
        ["Dep.Ked","Departemen Kedokteran"],
        ["PPDS","Departemen Kedokteran Spesialis"],
        ["D.Kep","Departemen Ilmu Keperawatan"],
        ["DIG","Departemen Ilmu Gizi"],
        ["AAK","Admin Subbagian Akademik & Kemahasiswaan"],
        ["AKK","Admin Subbagian Keuangan & Kepegawaian"],
        ["AUPA","Admin Subbagian Umum & Pengelolaan Aset"],
        ["SNT","Senat Fakultas"]];
        foreach($tujuan as $k => $v)
        {
            DB::table('tujuan')->insert([
                "kode" => $v[0],
                "nama" => $v[1],
                "created_at" => now(),
                "updated_at" => now()
            ]);            
        }
        //seeder untuk tabel Hal
        $hal = [["AK","Akreditasi"],
        ["BW","Beasiswa"],
        ["DO","Dokumentasi"],
        ["DK","Dosen dan Tenaga Kependidikan"],
        ["EP","Evaluasi Pendidikan"],
        ["HM","Hubungan Masyarakat"],
        ["HK","Hukum"],
        ["IN","Inovasi"],
        ["KI","Kalibrasi"],
        ["KL","Kelembagaan"],
        ["KM","Kemahasiswaan"],
        ["KP","Kepegawaian"],
        ["KS","Kerja Sama"],
        ["RT","Kerumahtanggaan"],
        ["TU","Ketatausahaan"],
        ["KU","Keuangan"],
        ["KR","Kurikulum"],
        ["MI","Media Informasi"],
        ["MK","Media Kreatif"],
        ["OT","Organisasi dan Tata Laksana"],
        ["DL","Pendidikan dan Pelatihan"],
        ["PP","Penelitian dan Pengembangan"],
        ["PM","Pengabdian kepada Masyarakat"],
        ["PW","Pengawasan"],
        ["PG","Pengembangan"],
        ["IL","Penyetaraan Ijazah Luar Negeri"],
        ["PR","Perencanaan"],
        ["PI","Perizinan"],
        ["PL","Perlengkapan"],
        ["PK","Perpustakaan"],
        ["PB","Publikasi Ilmiah"],
        ["SP","Sarana Pendidikan"],
        ["RG","Registrasi Pendidikan"],
        ["SR","Sertifikasi"],
        ["SE","Surat Edaran"],
        ["SK","Surat Kuasa"],
        ["TI","Teknologi Informasi"],
        ["PJ","Penjaminan Mutu"],
        ["LL","Lain-lain"]];
        foreach($hal as $k => $v)
        {
            DB::table('hal')->insert([
                "kode" => $v[0],
                "nama" => $v[1],
                "created_at" => now(),
                "updated_at" => now()
            ]);            
        }
        $sifat = [["1","Biasa"],["2","Penting"],["3","Segera"],["4","Rahasia"]];
        foreach($sifat as $k => $v)
        {
            DB::table('sifat')->insert([
                "kode" => $v[0],
                "nama" => $v[1],
                "created_at" => now(),
                "updated_at" => now()
            ]);
        }
        for($i = 1;$i <= 10;$i++)
        {
            DB::table('suratKeluar')->insert([
                "nomorAgenda"=> $i,
                "nomorSurat"=> $i,
                "kodeUnit"=>"UN7.5.4.1",
                "kodeHal"=>"KU",
                "sifatSurat"=>"1",
                "status"=>'digunakan',
                "tanggalPengesahan"=>now(),
                // "asalSurat"=> "Ketua Departemen Gizi",
                "tujuanSurat"=>"RSND",
                "disahkanOleh"=>"Dekan",
                "lampiran"=>"oke.jpg",
                "jumlahLampiran"=>2,
                "perihal"=>"OKee ini adalah perihal",
                "created_at"=>now(),
                "updated_at"=>now(),
                "created_by"=>1,

            ]);
        }
        for($i = 1;$i <= 10;$i++)
        {
            DB::table('suratMasuk')->insert([
                "nomorAgenda"=> $i,
                "nomorSurat"=> $i."/UN7.F4/2015/X",
                "kodeHal"=>"KU",
                "tujuanSurat"=>"DEAN",
                "sifatSurat"=>"1",
                "tanggalPengajuan"=>now(),
                "asalSurat"=> "KETUA DEPARTEMEN ILMU GIZI",
                "jumlahLampiran"=>2,
                "created_by"=>1,
                "lampiran"=>"oke.jpg",
                "perihal"=>"Permohonan Kenaikan Gaji Pegawai Kontrak",
                "created_at"=>now(),
                "updated_at"=>now()
            ]);
        }
        $max = DB::table('suratKeluar')->max('nomorAgenda');
        for($i = 1;$i <= 20;$i++)
        {
            if($i<=10){
                DB::table('suratKeluar')->insert([
                    "nomorAgenda"=> $max+$i,
                    "kodeUnit"=>"UN7.5.4.1",
                    "nomorSurat"=> $max+$i,
                    "kodeHal"=>"KU",
                    "tujuanSurat"=>"RSND",
                    "sifatSurat"=>"1",
                    "tanggalPengesahan"=>now(),
                    "created_by"=>1,
                    // "asalSurat"=> "KETUA DEPARTEMEN ILMU GIZI",
                    "jumlahLampiran"=>2,
                    "disahkanOleh"=>"Dekan",
                    "status"=>"digunakan",
                    "lampiran"=>"oke.jpg",
                    "perihal"=>"Permohonan Kenaikan Gaji Pegawai Kontrak",
                    "created_at"=>now(),
                    'jenis'=>'antidatir',
                    "updated_at"=>now()
                ]);    
            }else{
                DB::table('suratKeluar')->insert([
                    "nomorAgenda"=> null,
                    "kodeUnit"=>"UN7.5.4.1",
                    "nomorSurat"=> $max+$i,
                    "kodeHal"=>"KU",
                    "tujuanSurat"=>"RSND",
                    "disahkanOleh"=>"Dekan",
                    "sifatSurat"=>"1",
                    "tanggalPengesahan"=>now(),
                    // "asalSurat"=> "KETUA DEPARTEMEN ILMU GIZI",
                    "jumlahLampiran"=>2,
                    "created_by"=>1,
                    "status"=>"belum",
                    "lampiran"=>"oke.jpg",
                    "perihal"=>"Permohonan Kenaikan Gaji Pegawai Kontrak",
                    "created_at"=>now(),
                    'jenis'=>'antidatir',
                    "updated_at"=>now()
                ]);
    
            }
        }


    }
}
