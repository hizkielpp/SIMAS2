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
            // Ambil tanggal sekarang
            $today = Carbon::now();
            // Sesuaikan format
            $todayFormated = $today->format('Y-m-d');
            $output = new ConsoleOutput();
            $max = DB::table('suratkeluar')
                ->where('tanggalPengesahan', $todayFormated)
                ->max('nomorSurat');

            // Ambil data hari libur menggunakan API
            $client = new Client();
            $response = $client->get('https://api-harilibur.vercel.app/api', [
                'headers' => [
                    'accept' => 'application/json'
                ]
            ]);
            $data = json_decode($response->getBody(), true);

            // Cek untuk menghindari hari sabtu/minggu dan hari libur nasional
            $isWorkingDay = true;
            foreach ($data as $value) {
                // Ambil tanggal hari libur dari API
                $dateOriginal = Carbon::createFromFormat('Y-m-d', $value['holiday_date']);
                // Sesuaikan format dengan format tanggal di API
                $value['holiday_date'] = $dateOriginal->format('Y-m-d');
                if ((($todayFormated == $value['holiday_date']) && $value['is_national_holiday']) || ($today == $today->isWeekend())) {
                    $isWorkingDay = false;
                };
            }

            // Jika hari kerja, maka generate antidatir
            if ($isWorkingDay) {
                for ($i = 1; $i <= 10; $i++) {
                    try {
                        DB::table('suratkeluar')->insert([
                            'tanggalPengesahan' => $todayFormated,
                            'created_at' => $todayFormated,
                            'updated_at' => $todayFormated,
                            'nomorSurat' => $max + $i,
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
            }
        })->dailyAt('23:59')->timezone('Asia/Jakarta');
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
