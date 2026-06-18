@extends('layouts.app')
@section('content')
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
        <form method="POST" action="{{ route('login') }}">
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
@endsection