@extends('layouts.app')
@section('title','Edit Pasien')
@section('breadcrumb','Pasien / Edit')
@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fas fa-pen me-2" style="color: var(--color-primary);"></i>Edit Data Pasien</h1>
        <p class="page-subtitle">{{ $pasien->nama_lengkap }}</p>
    </div>
    <a href="{{ route('pasien.show', $pasien) }}" class="btn btn-sm" style="background: transparent; border: 1.5px solid var(--color-border); color: var(--color-text-secondary); border-radius: 8px; padding: 8px 16px; font-weight: 600;">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="ncpms-card">
    <div class="card-title-custom">
        <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-id-card"></i></span>
        Ubah Identitas Pasien
    </div>
    <form method="POST" action="{{ route('pasien.update', $pasien) }}">
        @csrf @method('PUT')
        @include('pasien.form', ['pasien' => $pasien])
        <div class="d-flex gap-2 mt-2 pt-3 border-top">
            <button class="btn fw-bold px-4 py-2" style="background: var(--color-primary); color: white; border-radius: 10px; border: none;">
                <i class="fas fa-save me-1"></i> Simpan Perubahan
            </button>
            <a href="{{ route('pasien.show', $pasien) }}" class="btn fw-bold px-4 py-2" style="background: transparent; border: 1.5px solid var(--color-border); color: var(--color-text-secondary); border-radius: 10px;">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection
