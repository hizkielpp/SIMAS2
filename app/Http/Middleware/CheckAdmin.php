<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
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
        // if (Auth::id() == 1) {
        //     return $next($request);
        // } else {
        //     abort(403, 'Akses hanya untuk Admin!');
        //     // return redirect()->back()->with('failed', 'Akses hanya untuk admin');
        // }
        if ($request->session()->get('user')->role_id == 1) {
            return $next($request);
        } else {
            abort(403, 'Akses hanya untuk Admin!');
            // return redirect()->back()->with('failed', 'Akses hanya untuk admin');
        }
    }
}
