@extends('layouts.app')
@section('title','Edit Pasien')
@section('breadcrumb','Pasien / Edit')
@section('content')
<div class="page-header"><div><h1 class="page-title">Edit Pasien</h1><p class="page-subtitle">{{ $pasien->nama_lengkap }}</p></div></div>
<div class="ncpms-card">
    <form method="POST" action="{{ route('pasien.update', $pasien) }}">
        @csrf @method('PUT')
        @include('pasien.form', ['pasien' => $pasien])
        <button class="btn-primary-ncpms"><i class="fas fa-save"></i> Simpan Perubahan</button>
        <a href="{{ route('pasien.show', $pasien) }}" class="btn-outline-ncpms ms-2">Batal</a>
    </form>
</div>
@endsection
