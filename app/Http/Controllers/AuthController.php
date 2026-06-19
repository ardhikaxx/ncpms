<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\LoginHistory;
use App\Models\Pengguna;

class AuthController extends Controller
{
    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], $this->messages());

        $throttleKey = $this->throttleKey($request);
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $detik = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => 'Terlalu banyak percobaan login gagal. Coba lagi dalam '.ceil($detik / 60).' menit.',
            ])->onlyInput('email');
        }

        $credentials['status_aktif'] = true;

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            $request->session()->put('last_activity', time());
            Auth::user()->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);
            LoginHistory::create([
                'pengguna_id' => Auth::id(),
                'tipe_event' => 'login',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            return redirect()->intended('dashboard');
        }

        RateLimiter::hit($throttleKey, 900);
        $pengguna = Pengguna::where('email', $request->email)->first();
        if ($pengguna) {
            LoginHistory::create([
                'pengguna_id' => $pengguna->id,
                'tipe_event' => 'login_gagal',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return back()->withErrors([
            'email' => 'Email atau kata sandi tidak sesuai, atau akun tidak aktif.',
        ])->onlyInput('email');
    }

    public function logout(Request $request) {
        if (Auth::check()) {
            LoginHistory::create([
                'pengguna_id' => Auth::id(),
                'tipe_event' => 'logout',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    private function messages()
    {
        return [
            'required' => ':attribute wajib diisi.',
            'email' => ':attribute harus berupa alamat email yang valid.',
        ];
    }

    private function throttleKey(Request $request): string
    {
        return Str::lower($request->input('email')).'|'.$request->ip();
    }
}
