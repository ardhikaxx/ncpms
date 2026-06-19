<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlindedAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->peran === 'admin_ti') {
            $routeName = (string) optional($request->route())->getName();
            $blockedNames = [
                'pasien.*',
                'kunjungan.*',
                'diagnosis.*',
                'intervensi.*',
                'monitoring.*',
                'laporan.*',
            ];

            foreach ($blockedNames as $pattern) {
                if ($request->routeIs($pattern)) {
                    abort(403, 'Admin TI tidak boleh mengakses data klinis pasien.');
                }
            }
        }

        return $next($request);
    }
}
