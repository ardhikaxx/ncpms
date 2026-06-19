<?php
namespace App\Http\Middleware;

use App\Models\LoginHistory;
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
                LoginHistory::create([
                    'pengguna_id' => Auth::id(),
                    'tipe_event' => 'timeout',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect('/login')->withErrors(['email' => 'Sesi Anda telah berakhir karena tidak ada aktivitas (Timeout). Silakan login kembali.']);
            }
            session(['last_activity' => time()]);
        }
        return $next($request);
    }
}
