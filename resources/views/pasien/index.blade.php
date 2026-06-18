@extends('layouts.app')
@section('content')
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
                    <td>{{ $p->tanggal_lahir->format('d M Y') }}</td>
                    <td>{{ $p->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>
                        <button class="btn btn-sm" style="background-color: var(--color-accent); color: white;">Detail</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $pasiens->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection