@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Penyelenggaraan Makanan & Dapur</h1>
        <p class="page-subtitle">Daftar permintaan diet pasien dari ruang rawat / instalasi gizi.</p>
    </div>
</div>

<div class="ncpms-card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Pasien & RM</th>
                    <th>No Kunjungan</th>
                    <th>Bentuk Makanan</th>
                    <th>Diet (Kalori/Protein)</th>
                    <th>Pantangan/Alergi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kunjungans as $kunjungan)
                    @php $p = $kunjungan->preskripsiDiets->first(); @endphp
                <tr>
                    <td>
                        <div class="fw-bold">{{ $kunjungan->pasien->nama_lengkap }}</div>
                        <div class="text-muted small">RM: {{ $kunjungan->pasien->nomor_rekam_medis }}</div>
                    </td>
                    <td><span class="rm-badge">{{ $kunjungan->nomor_kunjungan }}</span></td>
                    <td><strong>{{ strtoupper(str_replace('_', ' ', $p->bentuk_makanan)) }}</strong></td>
                    <td>
                        {{ $p->tujuan_terapi }}<br>
                        <span class="badge-pill badge-soft-success">{{ $p->total_kebutuhan_energi_kkal }} kkal</span>
                        <span class="badge-pill badge-soft-primary">{{ $p->gram_protein }}g P</span>
                    </td>
                    <td>
                        @if($p->pantangan_spesifik)
                            <span class="text-danger fw-bold"><i class="fas fa-exclamation-triangle"></i> {{ $p->pantangan_spesifik }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('dapur.cetak', $p->id) }}" target="_blank" class="btn-sm-ncpms btn-ncpms-outline">
                            <i class="fas fa-print"></i> Cetak Etiket
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">Belum ada order makanan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
