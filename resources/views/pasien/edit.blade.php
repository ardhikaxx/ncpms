@extends('layouts.app')
@section('title','Edit Pasien')
@section('breadcrumb','Pasien / Edit')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Data Pasien</h1>
        <p class="page-subtitle">{{ $pasien->nama_lengkap }}</p>
    </div>
    <a href="{{ route('pasien.show', $pasien) }}" class="btn-ncpms-outline">
        &larr; Kembali
    </a>
</div>

<div class="ncpms-card">
    <div class="card-title-custom">Ubah Identitas Pasien</div>
    <form method="POST" action="{{ route('pasien.update', $pasien) }}">
        @csrf @method('PUT')
        @include('pasien.form', ['pasien' => $pasien])
        <div class="d-flex gap-2 mt-2 pt-3 border-top">
            <button type="submit" class="btn-ncpms">
                Simpan Perubahan
            </button>
            <a href="{{ route('pasien.show', $pasien) }}" class="btn-ncpms-outline">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection
