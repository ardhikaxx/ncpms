@extends('layouts.app')
@section('title','Pasien Baru')
@section('breadcrumb','Pasien / Baru')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Registrasi Pasien Baru</h1>
        <p class="page-subtitle">Tambahkan pasien ke Master Patient Index (MPI).</p>
    </div>
    <a href="{{ route('pasien.index') }}" class="btn-ncpms-outline">
        &larr; Kembali
    </a>
</div>

<div class="ncpms-card">
    <div class="card-title-custom">Data Identitas Pasien</div>
    <form method="POST" action="{{ route('pasien.store') }}">
        @csrf
        @include('pasien.form', ['pasien' => null])
        <div class="d-flex gap-2 mt-2 pt-3 border-top">
            <button type="submit" class="btn-ncpms">
                Simpan Pasien
            </button>
            <a href="{{ route('pasien.index') }}" class="btn-ncpms-outline">
                Batal
            </a>
        </div>
    </form>
</div>

@endsection
