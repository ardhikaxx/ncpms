@extends('layouts.app')
@section('title','Master Pasien')
@section('breadcrumb','Pasien')

@push('styles')
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        .avatar-circle {
            width: 38px; height: 38px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; color: white; font-size: 1rem;
            background: var(--color-primary); flex-shrink: 0;
        }
        .avatar-circle.female { background: var(--color-primary-dark); }
        .data-table thead th {
            background: #f8faf9; font-size: 0.75rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.05em;
            color: var(--color-text-muted); border-bottom: 1px solid var(--color-border);
            padding: 12px 14px; white-space: nowrap;
        }
        .data-table tbody tr { transition: background 0.15s; }
        .data-table tbody tr:hover { background: var(--color-primary-subtle); }
        .data-table td { padding: 12px 14px; vertical-align: middle; border-bottom: 1px solid var(--color-divider); }
        .rm-badge {
            font-family: var(--font-mono); font-size: 0.82rem; font-weight: 700;
            background: var(--color-primary-subtle); color: var(--color-primary);
            padding: 3px 9px; border-radius: 6px; display: inline-block;
        }
    </style>
@endpush

@section('content')

<div class="page-header" data-aos="fade-down">
    <div>
        <h1 class="page-title"><i class="fas fa-users me-2" style="color: var(--color-primary); font-size: 1.5rem;"></i>Master Data Pasien</h1>
        <p class="page-subtitle">Kelola Master Patient Index (MPI). Identitas pasien ditampilkan secara tersamar untuk privasi.</p>
    </div>
    <a href="{{ route('pasien.create') }}"
        class="btn fw-bold px-4 py-2"
        style="background: var(--color-primary); color: white; border-radius: 10px; box-shadow: 0 4px 12px rgba(18,130,96,0.25); border: none; font-size: 0.9rem;">
        <i class="fas fa-user-plus me-2"></i> Registrasi Pasien Baru
    </a>
</div>

<div class="ncpms-card" data-aos="fade-up" data-aos-delay="80">
    <div class="table-responsive" style="border-radius: 10px; border: 1px solid var(--color-border);">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="padding-left: 20px;">No. RM</th>
                    <th>Nama Pasien</th>
                    <th>Demografi</th>
                    <th>Status</th>
                    <th>Kunjungan Terakhir</th>
                    <th class="text-end" style="padding-right: 20px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pasiens as $p)
                <tr>
                    <td style="padding-left: 20px;">
                        <span class="rm-badge">{{ $p->nomor_rm_tersamar }}</span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="avatar-circle {{ $p->jenis_kelamin === 'P' ? 'female' : '' }}">
                                {{ substr($p->nama_tersamar, 0, 1) }}
                            </div>
                            <span class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $p->nama_tersamar }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark" style="font-size: 0.85rem;">
                            <i class="fas fa-birthday-cake text-muted me-1"></i>{{ $p->tanggal_lahir?->age }} tahun
                        </div>
                        <div class="text-muted" style="font-size: 0.78rem;">
                            <i class="fas fa-venus-mars text-muted me-1"></i>{{ $p->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </div>
                    </td>
                    <td>
                        <span class="badge rounded-pill {{ $p->status_aktif ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-secondary-subtle text-secondary border border-secondary-subtle' }}" style="padding: 5px 12px; font-size: 0.75rem;">
                            <i class="fas {{ $p->status_aktif ? 'fa-circle-check' : 'fa-circle-xmark' }} me-1"></i>
                            {{ $p->status_aktif ? 'AKTIF' : 'NONAKTIF' }}
                        </span>
                    </td>
                    <td>
                        <span class="text-dark fw-bold" style="font-size: 0.85rem;">
                            <i class="far fa-calendar-alt text-muted me-1"></i>
                            {{ optional($p->kunjungans->first())->tanggal_kunjungan?->format('d M Y') ?? '-' }}
                        </span>
                    </td>
                    <td class="text-end" style="padding-right: 20px;">
                        <div class="d-flex justify-content-end gap-1">
                            <a href="{{ route('pasien.show', $p) }}"
                                class="btn btn-sm btn-outline-primary"
                                style="border-radius: 8px; font-size: 0.8rem; padding: 5px 12px;"
                                title="Detail Pasien">
                                <i class="fas fa-id-card me-1"></i>Detail
                            </a>
                            <a href="{{ route('pasien.edit', $p) }}"
                                class="btn btn-sm btn-outline-secondary"
                                style="border-radius: 8px; font-size: 0.8rem; padding: 5px 12px;"
                                title="Edit Pasien">
                                <i class="fas fa-pen me-1"></i>Edit
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">
                        <i class="fas fa-users-slash fa-2x mb-2 d-block opacity-25"></i>
                        Belum ada data pasien dalam sistem.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $pasiens->links('pagination::bootstrap-5') }}</div>
</div>

@endsection

@push('scripts')
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>AOS.init({ duration: 700, once: true });</script>
@endpush
