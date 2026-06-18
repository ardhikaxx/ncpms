@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0" style="color: var(--color-text-primary);">Dashboard Klinis</h3>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Total Pasien</div>
            <div class="stat-value">{{ $totalPasien }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Kunjungan Hari Ini</div>
            <div class="stat-value">{{ $totalKunjunganHariIni }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="stat-label">Preskripsi Diet Aktif</div>
            <div class="stat-value">{{ $totalPreskripsiAktif }}</div>
        </div>
    </div>
</div>

<div class="ncpms-card">
    <h5 class="fw-bold mb-3" style="color: var(--color-primary-dark);">Kunjungan Hari Ini</h5>
    <div class="table-responsive">
        <table class="table">
            <thead style="background-color: var(--color-primary-subtle);">
                <tr>
                    <th>NO. KUNJUNGAN</th>
                    <th>NAMA PASIEN</th>
                    <th>TIPE KUNJUNGAN</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kunjungans as $k)
                <tr>
                    <td style="font-family: var(--font-mono); font-size: 0.8125rem;">{{ $k->nomor_kunjungan }}</td>
                    <td class="fw-bold">{{ decrypt($k->pasien->nama_lengkap) }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $k->tipe_kunjungan)) }}</td>
                    <td>
                        <span class="badge" style="background-color: var(--color-primary-subtle); color: var(--color-primary-dark);">
                            {{ strtoupper($k->status) }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-ncpms" style="border: 1px solid var(--color-primary); color: var(--color-primary);">Lihat Detail</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">Tidak ada kunjungan hari ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection