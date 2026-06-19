@extends('layouts.app')
@section('title', 'Masuk ke Sistem')
@section('content')
<style>
    .login-container {
        display: flex;
        min-height: 100vh;
        background: #fff;
    }
    
    .login-sidebar {
        flex: 1;
        background: linear-gradient(135deg, var(--color-primary-darker) 0%, var(--color-primary) 100%);
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 4rem;
        color: white;
    }
    
    .login-sidebar::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: radial-gradient(circle at top right, rgba(255,255,255,0.1) 0%, transparent 50%),
                    radial-gradient(circle at bottom left, rgba(26,122,100,0.4) 0%, transparent 50%);
        pointer-events: none;
    }
    
    .login-sidebar::after {
        content: '\f46a'; /* Leaf icon */
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: -10%;
        bottom: -5%;
        font-size: 40rem;
        color: rgba(255,255,255,0.03);
        transform: rotate(-15deg);
        pointer-events: none;
    }
    
    .login-brand {
        position: relative;
        z-index: 2;
    }
    
    .login-brand .logo-icon {
        width: 64px;
        height: 64px;
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(255,255,255,0.2);
    }
    
    .login-brand h1 {
        font-size: 3.5rem;
        font-weight: 800;
        letter-spacing: -0.03em;
        margin-bottom: 1rem;
        text-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    
    .login-brand p {
        font-size: 1.1rem;
        line-height: 1.6;
        color: rgba(255,255,255,0.8);
        max-width: 400px;
    }
    
    .login-form-area {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
        background: #fdfdfd;
        position: relative;
    }
    
    .login-card {
        width: 100%;
        max-width: 420px;
        animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    
    @keyframes slideUpFade {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .login-header {
        text-align: left;
        margin-bottom: 2.5rem;
    }
    
    .login-header h2 {
        font-size: 2rem;
        font-weight: 800;
        color: var(--color-text-primary);
        letter-spacing: -0.02em;
        margin-bottom: 0.5rem;
    }
    
    .login-input-group {
        margin-bottom: 1.5rem;
    }
    
    .login-input-group label {
        display: block;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--color-text-secondary);
        margin-bottom: 0.5rem;
    }
    
    .login-input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid var(--color-border);
        border-radius: 12px;
        font-size: 1rem;
        font-family: var(--font-secondary);
        transition: all 0.2s ease;
        background: #fff;
    }
    
    .login-input:focus {
        outline: none;
        border-color: var(--color-primary);
        box-shadow: 0 0 0 4px var(--color-primary-subtle);
    }
    
    .btn-login {
        width: 100%;
        padding: 14px;
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
        color: #fff;
        border: none;
        border-radius: 12px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(26, 122, 100, 0.2);
        margin-top: 1rem;
    }
    
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(26, 122, 100, 0.3);
    }
    
    .demo-hint {
        margin-top: 2rem;
        padding: 1rem;
        background: var(--color-primary-subtle);
        border-radius: 12px;
        border: 1px dashed var(--color-primary-border);
        font-size: 0.8rem;
        color: var(--color-primary-dark);
        text-align: center;
    }

    @media (max-width: 991px) {
        .login-sidebar { display: none; }
    }
</style>

<div class="login-container">
    <div class="login-sidebar">
        <div class="login-brand">
            <div class="logo-icon">
                <i class="fas fa-leaf text-white"></i>
            </div>
            <h1>NCPMS</h1>
            <p>Sistem Informasi Manajemen Pelayanan Asuhan Gizi Terpadu. Tingkatkan presisi asuhan, kurangi risiko malnutrisi.</p>
        </div>
    </div>
    <div class="login-form-area">
        <div class="login-card">
            <div class="login-header">
                <h2>Selamat Datang</h2>
                <p class="text-muted">Silakan masukkan kredensial Anda untuk mengakses sistem klinis.</p>
            </div>
            
            @if ($errors->any())
                <div class="alert alert-danger" style="border-radius: 12px; font-size: 0.85rem; border: 1px solid #ffc9c9;">
                    <i class="fas fa-exclamation-circle me-1"></i> {{ $errors->first() }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="login-input-group">
                    <label>Alamat Email / ID Pengguna</label>
                    <input type="email" name="email" class="login-input" required autofocus value="andika.spgk@ncpms.local" placeholder="email@rumah-sakit.com">
                </div>
                
                <div class="login-input-group">
                    <label>Kata Sandi</label>
                    <input type="password" name="password" class="login-input" required value="password123" placeholder="••••••••">
                </div>
                
                <button type="submit" class="btn-login">
                    Masuk ke Sistem <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </form>
            
            <div class="demo-hint">
                <i class="fas fa-info-circle me-1"></i> <strong>Mode Demo:</strong><br>
                Gunakan kredensial <em>andika.spgk@ncpms.local</em> dengan kata sandi <em>password123</em> untuk mengakses.
            </div>
        </div>
    </div>
</div>
@endsection