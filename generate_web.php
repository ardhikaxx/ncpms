<?php

$controllersDir = __DIR__ . '/app/Http/Controllers/';
$viewsDir = __DIR__ . '/resources/views/';
$routesFile = __DIR__ . '/routes/web.php';

// Create directories if not exist
@mkdir($viewsDir . 'layouts', 0777, true);
@mkdir($viewsDir . 'auth', 0777, true);
@mkdir($viewsDir . 'dashboard', 0777, true);
@mkdir($viewsDir . 'pasien', 0777, true);

// 1. AuthController
$authController = "<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request \$request) {
        \$credentials = \$request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(\$credentials)) {
            \$request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ])->onlyInput('email');
    }

    public function logout(Request \$request) {
        Auth::logout();
        \$request->session()->invalidate();
        \$request->session()->regenerateToken();
        return redirect('/');
    }
}
";
file_put_contents($controllersDir . 'AuthController.php', $authController);

// 2. DashboardController
$dashboardController = "<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Kunjungan;
use App\Models\PreskripsiDiet;

class DashboardController extends Controller
{
    public function index() {
        \$totalPasien = Pasien::count();
        \$totalKunjunganHariIni = Kunjungan::whereDate('tanggal_kunjungan', today())->count();
        \$totalPreskripsiAktif = PreskripsiDiet::where('status', 'aktif')->count();
        
        \$kunjungans = Kunjungan::with('pasien')->whereDate('tanggal_kunjungan', today())->get();

        return view('dashboard.index', compact('totalPasien', 'totalKunjunganHariIni', 'totalPreskripsiAktif', 'kunjungans'));
    }
}
";
file_put_contents($controllersDir . 'DashboardController.php', $dashboardController);

// 3. PasienController
$pasienController = "<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;

class PasienController extends Controller
{
    public function index() {
        \$pasiens = Pasien::paginate(10);
        return view('pasien.index', compact('pasiens'));
    }
}
";
file_put_contents($controllersDir . 'PasienController.php', $pasienController);

// 4. web.php Routes
$routes = "<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PasienController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/pasien', [PasienController::class, 'index'])->name('pasien.index');
});
";
file_put_contents($routesFile, $routes);

// 5. Layout App
$layoutApp = '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCPMS &mdash; Nutrition Care and Patient Management System</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --color-primary: #1A7A64;
            --color-primary-dark: #145C4B;
            --color-primary-darker: #0E3F35;
            --color-primary-light: #2BA882;
            --color-primary-subtle: #E8F5F1;
            --color-primary-border: #B2DDD4;
            --color-accent: #F4A830;
            --color-bg-page: #F0F4F3;
            --color-bg-card: #FFFFFF;
            --color-bg-sidebar: #0E3F35;
            --color-bg-topbar: #FFFFFF;
            --color-text-primary: #1C2A27;
            --color-text-secondary: #4A6560;
            --color-text-muted: #8AA09A;
            --color-border: #D4E6E1;
            --sidebar-width: 240px;
            --topbar-height: 60px;
            --font-primary: \'Plus Jakarta Sans\', sans-serif;
            --font-secondary: \'Inter\', sans-serif;
        }

        body {
            font-family: var(--font-primary);
            background-color: var(--color-bg-page);
            color: var(--color-text-primary);
        }

        .sidebar {
            width: var(--sidebar-width);
            background: var(--color-bg-sidebar);
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1030;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand .brand-name {
            font-weight: 800;
            font-size: 1.125rem;
            color: #FFFFFF;
            text-decoration: none;
        }

        .sidebar-menu-item a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            color: rgba(255,255,255,0.7);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .sidebar-menu-item a:hover, .sidebar-menu-item a.active {
            background: var(--color-primary);
            color: #FFFFFF;
            font-weight: 600;
        }

        .topbar {
            position: fixed;
            top: 0;
            left: var(--sidebar-width);
            right: 0;
            height: var(--topbar-height);
            background: var(--color-bg-topbar);
            border-bottom: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            padding: 0 24px;
            z-index: 1020;
            justify-content: flex-end;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            padding: 24px;
        }

        .ncpms-card {
            background: var(--color-bg-card);
            border-radius: 12px;
            border: 1px solid var(--color-border);
            box-shadow: 0 1px 3px rgba(26,122,100,0.06);
            padding: 24px;
            margin-bottom: 16px;
        }

        .stat-card {
            background: var(--color-bg-card);
            border-radius: 12px;
            border: 1px solid var(--color-border);
            padding: 20px 24px;
            position: relative;
            overflow: hidden;
            border-top: 3px solid var(--color-primary);
        }

        .stat-value {
            font-size: 1.875rem;
            font-weight: 800;
            color: var(--color-primary-dark);
            font-family: var(--font-secondary);
        }

        .btn-primary-ncpms {
            background: var(--color-primary);
            color: #FFFFFF;
            border: none;
            padding: 9px 20px;
            border-radius: 8px;
            font-weight: 600;
        }
        
        .btn-primary-ncpms:hover {
            background: var(--color-primary-dark);
            color: #FFFFFF;
        }
    </style>
</head>
<body>
    @auth
    <div class="sidebar">
        <div class="sidebar-brand">
            <a href="{{ route(\'dashboard\') }}" class="brand-name">NCPMS</a>
            <div style="color: rgba(255,255,255,0.5); font-size: 0.6875rem;">Presisi Gizi, Akurasi Asuhan</div>
        </div>
        <div class="sidebar-menu-label" style="font-size: 0.625rem; color: rgba(255,255,255,0.35); padding: 20px 20px 8px; text-transform: uppercase; font-weight: bold;">Menu Utama</div>
        <div class="sidebar-menu-item">
            <a href="{{ route(\'dashboard\') }}" class="{{ request()->routeIs(\'dashboard\') ? \'active\' : \'\' }}">
                <i class="fas fa-home menu-icon"></i> Dashboard
            </a>
        </div>
        <div class="sidebar-menu-item">
            <a href="{{ route(\'pasien.index\') }}" class="{{ request()->routeIs(\'pasien.*\') ? \'active\' : \'\' }}">
                <i class="fas fa-users menu-icon"></i> Pasien & Kunjungan
            </a>
        </div>
    </div>
    <div class="topbar">
        <div class="d-flex align-items-center gap-3">
            <span class="fw-bold" style="color: var(--color-text-primary);">{{ Auth::user()->nama_lengkap }} ({{ strtoupper(Auth::user()->peran) }})</span>
            <form action="{{ route(\'logout\') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-sign-out-alt"></i> Keluar</button>
            </form>
        </div>
    </div>
    <div class="main-content">
        @yield(\'content\')
    </div>
    @else
        @yield(\'content\')
    @endauth
</body>
</html>';
file_put_contents($viewsDir . 'layouts/app.blade.php', $layoutApp);

// 6. Login View
$loginView = '@extends(\'layouts.app\')
@section(\'content\')
<div class="d-flex align-items-center justify-content-center vh-100" style="background-color: var(--color-bg-sidebar);">
    <div class="ncpms-card" style="width: 100%; max-width: 400px;">
        <div class="text-center mb-4">
            <h3 class="fw-bold" style="color: var(--color-primary);">NCPMS</h3>
            <p class="text-muted" style="font-size: 0.875rem;">Silakan masuk ke akun Anda</p>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger" style="font-size: 0.875rem;">
                {{ $errors->first() }}
            </div>
        @endif
        <form method="POST" action="{{ route(\'login\') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold" style="color: var(--color-text-secondary); font-size: 0.8125rem;">Email</label>
                <input type="email" name="email" class="form-control" required autofocus value="andika.spgk@ncpms.local">
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold" style="color: var(--color-text-secondary); font-size: 0.8125rem;">Password</label>
                <input type="password" name="password" class="form-control" required value="password123">
            </div>
            <button type="submit" class="btn btn-primary-ncpms w-100">Masuk</button>
        </form>
        <div class="mt-3 text-center" style="font-size: 0.75rem; color: var(--color-text-muted);">
            Gunakan andika.spgk@ncpms.local / password123
        </div>
    </div>
</div>
@endsection';
file_put_contents($viewsDir . 'auth/login.blade.php', $loginView);

// 7. Dashboard View
$dashboardView = '@extends(\'layouts.app\')
@section(\'content\')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0" style="color: var(--color-text-primary);">Dashboard Klinis</h3>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Total Pasien</div>
            <div class="stat-value">{{ $totalPasien }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Kunjungan Hari Ini</div>
            <div class="stat-value">{{ $totalKunjunganHariIni }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Preskripsi Diet Aktif</div>
            <div class="stat-value">{{ $totalPreskripsiAktif }}</div>
        </div>
    </div>
</div>

<div class="ncpms-card">
    <h5 class="fw-bold mb-3" style="color: var(--color-primary-dark);">Kunjungan Hari Ini</h5>
    <div class="table-responsive">
        <table class="table">
            <thead style="background-color: var(--color-primary-subtle);">
                <tr>
                    <th>NO. KUNJUNGAN</th>
                    <th>NAMA PASIEN</th>
                    <th>TIPE KUNJUNGAN</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kunjungans as $k)
                <tr>
                    <td style="font-family: var(--font-mono); font-size: 0.8125rem;">{{ $k->nomor_kunjungan }}</td>
                    <td class="fw-bold">{{ decrypt($k->pasien->nama_lengkap) }}</td>
                    <td>{{ ucfirst(str_replace(\'_\', \' \', $k->tipe_kunjungan)) }}</td>
                    <td>
                        <span class="badge" style="background-color: var(--color-primary-subtle); color: var(--color-primary-dark);">
                            {{ strtoupper($k->status) }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-ncpms" style="border: 1px solid var(--color-primary); color: var(--color-primary);">Lihat Detail</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Tidak ada kunjungan hari ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection';
file_put_contents($viewsDir . 'dashboard/index.blade.php', $dashboardView);

// 8. Pasien View
$pasienView = '@extends(\'layouts.app\')
@section(\'content\')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0" style="color: var(--color-text-primary);">Master Data Pasien</h3>
    <button class="btn btn-primary-ncpms"><i class="fas fa-plus"></i> Pasien Baru</button>
</div>

<div class="ncpms-card">
    <div class="table-responsive">
        <table class="table">
            <thead style="background-color: var(--color-primary-subtle);">
                <tr>
                    <th>NO. RM</th>
                    <th>NAMA PASIEN</th>
                    <th>TANGGAL LAHIR</th>
                    <th>JENIS KELAMIN</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pasiens as $p)
                <tr>
                    <td style="font-family: var(--font-mono); font-size: 0.8125rem;">{{ decrypt($p->nomor_rekam_medis) }}</td>
                    <td class="fw-bold">{{ decrypt($p->nama_lengkap) }}</td>
                    <td>{{ $p->tanggal_lahir->format(\'d M Y\') }}</td>
                    <td>{{ $p->jenis_kelamin == \'L\' ? \'Laki-laki\' : \'Perempuan\' }}</td>
                    <td>
                        <button class="btn btn-sm" style="background-color: var(--color-accent); color: white;">Detail</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $pasiens->links(\'pagination::bootstrap-5\') }}
    </div>
</div>
@endsection';
file_put_contents($viewsDir . 'pasien/index.blade.php', $pasienView);

echo "Web pages generated.\n";
