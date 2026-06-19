@extends('layouts.app')
@section('title','Master Pasien')
@section('breadcrumb','Pasien')

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .avatar-circle {
            width: 40px; height: 40px; border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; 
            font-weight: bold; color: white; font-size: 1.1rem;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
            background-color: var(--color-primary);
        }
        .avatar-circle.avatar-female {
            background-color: var(--color-primary-dark);
        }
        .table-hover-premium tbody tr:hover {
            background-color: var(--color-primary-subtle);
            transition: all 0.2s ease;
        }
    </style>
@endpush

@section('content')

<div class="page-header animate__animated animate__fadeIn">
    <div>
        <h1 class="page-title text-primary"><i class="fas fa-users me-2"></i>Master Data Pasien</h1>
        <p class="page-subtitle">Kelola Master Patient Index (MPI). Identitas pasien ditampilkan secara tersamar untuk privasi.</p>
    </div>
    <a href="{{ route('pasien.create') }}" class="btn btn-primary fw-bold" style="background-color: var(--color-primary); border: none;">
        <i class="fas fa-user-plus me-2"></i> Registrasi Pasien Baru
    </a>
</div>

<div class="ncpms-card shadow-sm mb-0" data-aos="fade-up" data-aos-delay="100">
    <div class="table-responsive" style="border-radius: 8px; border: 1px solid var(--color-border);">
        <table class="table align-middle table-hover-premium mb-0">
            <thead style="background-color: #f8f9fa;">
                <tr>
                    <th class="ps-4">No. RM</th>
                    <th>Nama Pasien</th>
                    <th>Demografi</th>
                    <th>Status</th>
                    <th>Kunjungan Terakhir</th>
                    <th class="text-end pe-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($pasiens as $p)
                <tr>
                    <td class="ps-4">
                        <div class="text-mono fw-bold text-primary" style="font-size: 0.95rem; background: var(--color-primary-subtle); padding: 4px 8px; border-radius: 6px; display: inline-block;">
                            {{ $p->nomor_rm_tersamar }}
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle {{ $p->jenis_kelamin === 'P' ? 'avatar-female' : '' }}">
                                {{ substr($p->nama_tersamar, 0, 1) }}
                            </div>
                            <div class="fw-bold text-dark" style="font-size: 1.05rem;">{{ $p->nama_tersamar }}</div>
                        </div>
                    </td>
                    <td>
                        <div class="text-dark fw-bold" style="font-size: 0.85rem;"><i class="fas fa-birthday-cake text-muted me-1"></i> {{ $p->tanggal_lahir?->age }} tahun</div>
                        <div class="text-muted small"><i class="fas fa-venus-mars text-muted me-1"></i> {{ $p->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                    </td>
                    <td>
                        <span class="badge px-3 py-2 rounded-pill @if($p->status_aktif) bg-success-subtle text-success border border-success-subtle @else bg-secondary-subtle text-secondary border border-secondary-subtle @endif">
                            {{ $p->status_aktif ? 'AKTIF' : 'NONAKTIF' }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-bold text-dark"><i class="far fa-calendar-alt text-muted me-1"></i> {{ optional($p->kunjungans->first())->tanggal_kunjungan?->format('d M Y') ?? 'Belum ada' }}</div>
                    </td>
                    <td class="text-end pe-4">
                        <a href="{{ route('pasien.show', $p) }}" class="btn btn-sm btn-outline-primary me-1" title="Detail Pasien">
                            <i class="fas fa-id-card"></i> Detail
                        </a>
                        <a href="{{ route('pasien.edit', $p) }}" class="btn btn-sm btn-outline-warning" title="Edit Pasien">
                            <i class="fas fa-pen"></i> Edit
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="fas fa-users-slash fa-3x mb-3 opacity-25"></i><br>
                        Belum ada data pasien dalam sistem.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $pasiens->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({ duration: 800, once: true });
</script>
@endpush
