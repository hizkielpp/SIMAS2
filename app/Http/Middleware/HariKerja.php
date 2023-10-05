<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

// Import class Carbon
use Carbon\Carbon;

class HariKerja
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Mendapatkan tanggal hari ini menggunakan Carbon
        $today = Carbon::now();

        // Memeriksa apakah hari ini adalah Sabtu atau Minggu
        if ($today->isWeekend()) {
            // Jika hari ini Sabtu atau Minggu, kembalikan respons berupa pesan
            // return view('hari-libur');
            return response()->view('hari-libur');
        }

        // Lanjutkan permintaan jika bukan hari Sabtu atau Minggu
        return $next($request);
    }
}
