@extends('layouts.app')
@section('title', 'Masuk ke Sistem')
@section('content')
<style>
    .login-wrap {
        display: flex;
        min-height: 100vh;
        background: #f0f4f2;
        font-family: var(--font-primary);
    }

    /* Left panel */
    .login-panel {
        width: 45%;
        background: var(--color-primary);
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 4rem 3.5rem;
        color: #fff;
        overflow: hidden;
    }
    .login-panel::before {
        content: '';
        position: absolute;
        width: 400px; height: 400px;
        border-radius: 50%;
        background: rgba(255,255,255,0.05);
        top: -120px; left: -100px;
    }
    .login-panel::after {
        content: '';
        position: absolute;
        width: 260px; height: 260px;
        border-radius: 50%;
        background: rgba(255,255,255,0.04);
        bottom: -80px; right: -60px;
    }
    .panel-logo {
        width: 60px; height: 60px;
        background: rgba(255,255,255,0.15);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(255,255,255,0.2);
        position: relative; z-index: 1;
    }
    .panel-title {
        font-size: 3.2rem;
        font-weight: 800;
        letter-spacing: -0.04em;
        line-height: 1;
        margin-bottom: 1rem;
        position: relative; z-index: 1;
    }
    .panel-desc {
        font-size: 1rem;
        line-height: 1.7;
        color: rgba(255,255,255,0.78);
        max-width: 380px;
        position: relative; z-index: 1;
    }
    .panel-features {
        margin-top: 2.5rem;
        display: flex;
        flex-direction: column;
        gap: 1rem;
        position: relative; z-index: 1;
    }
    .panel-feature-item {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 0.9rem;
        color: rgba(255,255,255,0.85);
    }
    .panel-feature-item i {
        width: 32px; height: 32px;
        background: rgba(255,255,255,0.12);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }

    /* Right form area */
    .login-form-area {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        background: #f0f4f2;
    }
    .login-card {
        width: 100%;
        max-width: 420px;
        background: #fff;
        border-radius: 20px;
        padding: 2.5rem 2.5rem;
        box-shadow: 0 8px 40px rgba(18,130,96,0.08), 0 2px 8px rgba(0,0,0,0.04);
        border: 1px solid var(--color-border);
        animation: slideUpFade 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes slideUpFade {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .login-heading {
        margin-bottom: 2rem;
    }
    .login-heading h2 {
        font-size: 1.75rem;
        font-weight: 800;
        color: var(--color-text-primary);
        letter-spacing: -0.02em;
        margin-bottom: 0.25rem;
    }
    .login-heading p {
        color: var(--color-text-muted);
        font-size: 0.9rem;
        margin: 0;
    }
    .form-group-login {
        margin-bottom: 1.2rem;
    }
    .form-group-login label {
        display: block;
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--color-text-secondary);
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }
    .form-input-login {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid var(--color-border);
        border-radius: 10px;
        font-size: 0.95rem;
        font-family: var(--font-secondary);
        transition: all 0.25s ease;
        background: #fafcfb;
        color: var(--color-text-primary);
        outline: none;
    }
    .form-input-login:focus {
        border-color: var(--color-primary);
        background: #fff;
        box-shadow: 0 0 0 4px var(--color-primary-subtle);
    }
    .input-icon-wrap {
        position: relative;
    }
    .input-icon-wrap i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--color-text-muted);
        font-size: 0.9rem;
    }
    .input-icon-wrap .form-input-login {
        padding-left: 40px;
    }
    .btn-login-submit {
        width: 100%;
        padding: 13px;
        background: var(--color-primary);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.25s ease;
        box-shadow: 0 4px 16px rgba(18,130,96,0.25);
        margin-top: 0.5rem;
        letter-spacing: 0.02em;
        font-family: var(--font-primary);
        display: flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-login-submit:hover {
        background: var(--color-primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(18,130,96,0.3);
    }
    .demo-box {
        margin-top: 1.5rem;
        padding: 1rem 1.25rem;
        background: var(--color-primary-subtle);
        border-radius: 10px;
        border: 1px dashed var(--color-primary-border);
        font-size: 0.82rem;
        color: var(--color-primary-dark);
    }
    .demo-box strong { color: var(--color-primary); }
    .demo-box code {
        background: rgba(18,130,96,0.1);
        padding: 1px 6px;
        border-radius: 4px;
        font-size: 0.82rem;
        color: var(--color-primary-dark);
    }

    @media (max-width: 900px) {
        .login-panel { display: none; }
        .login-card { padding: 2rem; }
    }
</style>

<div class="login-wrap">
    {{-- Left Panel --}}
    <div class="login-panel">
        <div class="panel-logo">
            <i class="fas fa-leaf text-white"></i>
        </div>
        <h1 class="panel-title">NCPMS</h1>
        <p class="panel-desc">Sistem Informasi Manajemen Asuhan Gizi Terpadu. Tingkatkan presisi asuhan, kurangi risiko malnutrisi.</p>
        <div class="panel-features">
            <div class="panel-feature-item">
                <i class="fas fa-shield-alt"></i>
                <span>Keamanan data pasien terstandar</span>
            </div>
            <div class="panel-feature-item">
                <i class="fas fa-chart-line"></i>
                <span>Analitik klinis real-time</span>
            </div>
            <div class="panel-feature-item">
                <i class="fas fa-user-md"></i>
                <span>Alur PAGT terintegrasi penuh</span>
            </div>
        </div>
    </div>

    {{-- Right Form --}}
    <div class="login-form-area">
        <div class="login-card">
            <div class="login-heading">
                <h2>Selamat Datang</h2>
                <p>Masukkan kredensial Anda untuk mengakses sistem.</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger d-flex align-items-center gap-2 mb-3" style="border-radius: 10px; font-size: 0.85rem; border: 1px solid #ffc9c9; background: #fff5f5;">
                    <i class="fas fa-exclamation-circle text-danger"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group-login">
                    <label>Email / ID Pengguna</label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="form-input-login" required autofocus
                            value="andika.spgk@ncpms.local" placeholder="email@rumah-sakit.com">
                    </div>
                </div>

                <div class="form-group-login">
                    <label>Kata Sandi</label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-input-login" required
                            value="password123" placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="btn-login-submit">
                    <i class="fas fa-sign-in-alt"></i> Masuk ke Sistem
                </button>
            </form>

            <div class="demo-box">
                <i class="fas fa-info-circle me-1"></i> <strong>Mode Demo:</strong><br>
                Email: <code>andika.spgk@ncpms.local</code> &bull; Password: <code>password123</code>
            </div>
        </div>
    </div>
</div>
@endsection
