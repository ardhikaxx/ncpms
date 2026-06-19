@extends('layouts.app')
@section('title','Master Pasien')
@section('breadcrumb','Pasien')
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Master Data Pasien</h1>
        <p class="page-subtitle">MPI klinis. Identitas pada daftar ditampilkan tersamar.</p>
    </div>
    <a href="{{ route('pasien.create') }}" class="btn-primary-ncpms"><i class="fas fa-plus"></i> Pasien Baru</a>
</div>

<div class="ncpms-card">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>NRM</th><th>Nama</th><th>Usia</th><th>JK</th><th>Status</th><th>Kunjungan Terakhir</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($pasiens as $p)
                <tr>
                    <td class="text-mono">{{ $p->nomor_rm_tersamar }}</td>
                    <td class="fw-semibold">{{ $p->nama_tersamar }}</td>
                    <td>{{ $p->tanggal_lahir?->age }} tahun</td>
                    <td>{{ $p->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>{{ $p->status_aktif ? 'Aktif' : 'Nonaktif' }}</td>
                    <td>{{ optional($p->kunjungans->first())->tanggal_kunjungan?->format('d/m/Y') ?? '-' }}</td>
                    <td class="d-flex gap-1">
                        <a href="{{ route('pasien.show', $p) }}" class="btn-outline-ncpms btn-sm-ncpms"><i class="fas fa-eye"></i> Detail</a>
                        <a href="{{ route('pasien.edit', $p) }}" class="btn-outline-ncpms btn-sm-ncpms"><i class="fas fa-pen"></i></a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada data pasien.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    {{ $pasiens->links('pagination::bootstrap-5') }}
</div>
@endsection
