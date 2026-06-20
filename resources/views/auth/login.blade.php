@extends('layouts.app')
@section('title', 'Masuk ke Sistem')

@push('styles')
<style>
    .login-root {
        display: flex;
        min-height: 100vh;
        background: var(--color-bg-page);
        font-family: var(--font-primary);
    }

    /* Left Panel */
    .login-left {
        width: 42%;
        background: #0f766e;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 3.5rem 3rem;
        color: #fff;
    }

    .login-brand {
        font-size: 2.4rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        line-height: 1;
        margin-bottom: 0.75rem;
    }

    .login-tagline {
        font-size: 0.92rem;
        color: rgba(255,255,255,0.75);
        line-height: 1.7;
        margin-bottom: 2.5rem;
        max-width: 340px;
    }

    .login-features {
        display: flex;
        flex-direction: column;
        gap: 0.85rem;
    }

    .login-feature {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.86rem;
        color: rgba(255,255,255,0.8);
    }

    .login-feature-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: rgba(255,255,255,0.45);
        flex-shrink: 0;
    }

    /* Right Panel */
    .login-right {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }

    .login-card {
        width: 100%;
        max-width: 400px;
        background: #fff;
        border-radius: var(--radius-lg);
        padding: 2.25rem;
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--color-border);
        animation: loginFadeIn 0.35s ease-out forwards;
    }

    @keyframes loginFadeIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .login-card-title {
        font-size: 1.45rem;
        font-weight: 800;
        color: var(--color-text-primary);
        letter-spacing: -0.02em;
        margin-bottom: 0.2rem;
    }

    .login-card-sub {
        font-size: 0.86rem;
        color: var(--color-text-muted);
        margin-bottom: 1.5rem;
    }

    .login-label {
        display: block;
        font-size: 0.78rem;
        font-weight: 600;
        color: var(--color-text-secondary);
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
    }

    .login-input {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid var(--color-border);
        border-radius: var(--radius-sm);
        font-size: 0.9rem;
        font-family: var(--font-primary);
        background: #fff;
        color: var(--color-text-primary);
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .login-input:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.08);
    }

    .login-error {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
        border-radius: var(--radius-sm);
        padding: 10px 14px;
        font-size: 0.84rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 1rem;
    }

    .demo-info {
        margin-top: 1.25rem;
        padding: 0.85rem 1rem;
        background: var(--color-primary-subtle);
        border-radius: var(--radius-sm);
        border: 1px dashed var(--color-primary-border);
        font-size: 0.8rem;
        color: var(--color-primary-dark);
        line-height: 1.7;
    }

    .demo-info code {
        background: rgba(15, 118, 110, 0.1);
        padding: 1px 5px;
        border-radius: 4px;
        font-size: 0.8rem;
    }

    @media (max-width: 860px) {
        .login-left { display: none; }
    }
</style>
@endpush

@section('content')
<div class="login-root">
    <div class="login-left">
        <div class="login-brand">NCPMS</div>
        <p class="login-tagline">Sistem Informasi Manajemen Asuhan Gizi Terpadu untuk pelayanan klinis yang presisi dan terstandar.</p>
        <div class="login-features">
            <div class="login-feature">
                <div class="login-feature-dot"></div>
                <span>Asesmen nutrisi berbasis ADIME</span>
            </div>
            <div class="login-feature">
                <div class="login-feature-dot"></div>
                <span>Preskripsi diet dengan kalkulator kalori</span>
            </div>
            <div class="login-feature">
                <div class="login-feature-dot"></div>
                <span>Monitoring & evaluasi PAGT terpadu</span>
            </div>
            <div class="login-feature">
                <div class="login-feature-dot"></div>
                <span>Laporan statistik & audit mutu gizi</span>
            </div>
        </div>
    </div>

    <div class="login-right">
        <div class="login-card">
            <div class="login-card-title">Selamat Datang</div>
            <div class="login-card-sub">Masukkan kredensial Anda untuk mengakses sistem.</div>

            @if ($errors->any())
                <div class="login-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <label class="login-label">Email / ID Pengguna</label>
                <input type="email" name="email" class="login-input mb-3" required autofocus
                    value="andika.spgk@ncpms.local" placeholder="email@rumah-sakit.com">

                <label class="login-label">Kata Sandi</label>
                <div class="position-relative mb-3">
                    <input type="password" id="loginPassword" name="password" class="login-input" required
                        value="password123" placeholder="••••••••">
                    <span class="position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #94a3b8;" onclick="togglePassword('loginPassword', this)">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>

                <button type="submit" class="btn-ncpms w-100 py-2" style="font-size: 0.92rem;">
                    <i class="fas fa-sign-in-alt me-1"></i> Masuk ke Sistem
                </button>
            </form>

            <div class="demo-info">
                <strong>Mode Demo:</strong><br>
                Email: <code>andika.spgk@ncpms.local</code> &bull; Password: <code>password123</code>
            </div>
        </div>
    </div>
</div>
@endsection
