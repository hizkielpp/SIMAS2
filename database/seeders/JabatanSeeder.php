<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JabatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Jabatan seeder start
        $jabatans = [
            'Dekan',
            'Wakil Dekan I',
            'Wakil Dekan II',
            'Manager Bagian Tata Usaha',
            'Supervisor Akademik & Kemahasiswaan',
            'Supervisor Sumber Daya',
            'Tenaga Kependidikan',
        ];
        foreach ($jabatans as $key) {
            # code...
            DB::table('jabatans')->insert([
                'nama_jabatan' => $key,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        // Jabatan seeder end
    }
}
