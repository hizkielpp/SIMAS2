<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Role seeder start
        $role = [['1', 'Admin'], ['2', 'Operator'], ['3', 'Pimpinan']];
        foreach ($role as $k => $v) {
            DB::table('role')->insert([
                'kode' => $v[0],
                'nama' => $v[1],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        // Role seeder start
    }
}
