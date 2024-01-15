<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use GuzzleHttp\Client;
use Symfony\Component\Console\Output\ConsoleOutput;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            for ($a = 1; $a <= 10; $a++) {
                // Ambil tanggal sekarang
                $today = Carbon::now();
                // Sesuaikan format
                $todayFormated = $today->format('Y-m-d');
                $output = new ConsoleOutput();
                $max = DB::table('suratkeluar')
                    ->where('tanggalPengesahan', $todayFormated)
                    ->max('nomorSurat');

                // Cek untuk menghindari hari sabtu/minggu dan hari libur nasional
                $client = new Client();
                $response = $client->get('https://api-harilibur.vercel.app/api', [
                    'headers' => [
                        'accept' => 'application/json'
                    ]
                ]);
                $data = json_decode($response->getBody(), true);
                $false = true;
                foreach ($data as $value) {
                    if (date('Y-m-d') == $value['holiday_date']) {
                        $false = false;
                    };
                }
                if ($false) {
                    for ($a = 1; $a <= 10; $a++) {
                        try {
                            DB::table('suratkeluar')->insert([
                                'tanggalPengesahan' => $todayFormated,
                                'created_at' => $todayFormated,
                                'updated_at' => $todayFormated,
                                'nomorSurat' => $max + $a,
                                'kodeHal' => 'AK',
                                'sifatSurat' => 1,
                                'jenis' => 'antidatir',
                                'status' => 'belum',
                                'tujuanSurat' => 'dummy',
                                'lampiran' => 'dummy',
                                'jumlahLampiran' => 1,
                                'created_by' => 777,
                                'kodeUnit' => 'dummy',
                                'disahkanOleh' => 'dummy'
                            ]);
                        } catch (\Exception $e) {
                            $output->writeln("<info>" . $e  . "</info>");
                        }
                    }
                } else {
                }
            }
            // // Ambil tanggal sekarang
            // $today = Carbon::now();
            // // Sesuaikan format
            // $todayFormated = $today->format('Y-m-d');

            // // Ambil max nomor surat antidatir
            // $max = DB::table('suratkeluar')
            //     ->where('tanggalPengesahan', $todayFormated)
            //     ->max('nomorSurat');

            // // Cek untuk menghindari hari sabtu/minggu dan hari libur nasional
            // $client = new Client();
            // $response = $client->get('https://api-harilibur.vercel.app/api', [
            //     'headers' => [
            //         'accept' => 'application/json'
            //     ]
            // ]);
            // $data = json_decode($response->getBody(), true);
            // $false = true;
            // foreach ($data as $value) {
            //     if (date('Y-m-d') == $value['holiday_date']) {
            //         $false = false;
            //     };
            // }
            // if ($false) {
            //     for ($a = 1; $a <= 10; $a++) {
            //         DB::table('suratkeluar')->insert([
            //             'tanggalPengesahan' => $todayFormated,
            //             'created_at' => $todayFormated,
            //             'updated_at' => $todayFormated,
            //             'nomorSurat' => $max + $a,
            //             'kodeHal' => 'dummy',
            //             'sifatSurat' => 'biasa',
            //             'jenis' => 'antidatir',
            //             'status' => 'belum',
            //             'tujuanSurat' => 'dummy',
            //             'lampiran' => 'dummy',
            //             'jumlahLampiran' => 1,
            //             'created_by' => 1,
            //             'kodeUnit' => 'dummy',
            //             'disahkanOleh' => 'dummy'
            //         ]);
            //     }
            // } else {
            // }
        })->everyMinute()->weekdays()->timezone('Asia/Jakarta');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
