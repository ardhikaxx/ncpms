<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SessionTimeoutMiddleware
{
    public function handle($request, Closure $next)
    {
        $timeout = env('SESSION_LIFETIME', 15) * 60;
        
        if (Auth::check()) {
            $lastActivity = session('last_activity', time());
            if (time() - $lastActivity > $timeout) {
                Auth::logout();
                $request->session()->invalidate();
                return redirect('/login')->withErrors(['email' => 'Sesi Anda telah berakhir karena tidak ada aktivitas (Timeout). Silakan login kembali.']);
            }
            session(['last_activity' => time()]);
        }
        return $next($request);
    }
}
