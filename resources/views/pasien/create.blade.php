@extends('layouts.app')
@section('title','Pasien Baru')
@section('breadcrumb','Pasien / Baru')
@section('content')
<div class="page-header"><div><h1 class="page-title">Pasien Baru</h1><p class="page-subtitle">Registrasi Master Patient Index.</p></div></div>
<div class="ncpms-card">
    <form method="POST" action="{{ route('pasien.store') }}">
        @csrf
        @include('pasien.form', ['pasien' => null])
        <button class="btn-primary-ncpms"><i class="fas fa-save"></i> Simpan Pasien</button>
        <a href="{{ route('pasien.index') }}" class="btn-outline-ncpms ms-2">Batal</a>
    </form>
</div>
@endsection
