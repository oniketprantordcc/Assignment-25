<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class isAdminInventoryAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user() && Auth::user()->hasRole('Aplikasi Gudang') || Auth::user()->hasRole('Aplikasi Purchasing') || Auth::user()->hasRole('Aplikasi Accounting') || Auth::user()->pegawai->jabatan->nama_jabatan == 'Owner') {
            return $next($request);
        }
        abort(403);
    }
}
