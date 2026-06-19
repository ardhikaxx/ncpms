@extends('layouts.app')
@section('title','Master Pasien')
@section('breadcrumb','Pasien')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Master Data Pasien</h1>
        <p class="page-subtitle">Kelola Master Patient Index (MPI). Identitas ditampilkan secara tersamar untuk privasi.</p>
    </div>
    <a href="{{ route('pasien.create') }}" class="btn-ncpms">
        + Registrasi Pasien Baru
    </a>
</div>

<div class="ncpms-card">
    <div class="table-responsive">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th>No. RM</th>
                    <th>Nama Pasien</th>
                    <th>Demografi</th>
                    <th>Status</th>
                    <th>Kunjungan Terakhir</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pasiens as $p)
                <tr>
                    <td>
                        <span class="rm-badge">{{ $p->nomor_rm_tersamar }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-circle">
                                {{ substr($p->nama_tersamar, 0, 1) }}
                            </div>
                            <span class="fw-semibold">{{ $p->nama_tersamar }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $p->tanggal_lahir?->age }} tahun</div>
                        <div class="text-muted small">{{ $p->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                    </td>
                    <td>
                        <span class="badge-pill {{ $p->status_aktif ? 'badge-soft-success' : 'badge-soft-gray' }}">
                            {{ $p->status_aktif ? 'AKTIF' : 'NONAKTIF' }}
                        </span>
                    </td>
                    <td>
                        <span class="text-muted fw-semibold">
                            {{ optional($p->kunjungans->first())->tanggal_kunjungan?->format('d M Y') ?? '-' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('pasien.show', $p) }}" class="btn-sm-ncpms">Detail</a>
                            <a href="{{ route('pasien.edit', $p) }}" class="btn-sm-ncpms btn-ncpms-outline">Edit</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="empty-state">
                            <i class="fas fa-users fa-2x d-block"></i>
                            <h5>Belum Ada Data</h5>
                            <p>Belum ada data pasien dalam sistem.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $pasiens->links('pagination::bootstrap-5') }}</div>
</div>

@endsection
