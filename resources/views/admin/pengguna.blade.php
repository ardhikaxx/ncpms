@extends('layouts.app')
@section('title','Manajemen Pengguna')
@section('breadcrumb','Admin / Pengguna')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fas fa-user-shield me-2" style="color: var(--color-primary);"></i>Manajemen Akun & Hak Akses</h1>
        <p class="page-subtitle">Kontrol penuh atas otorisasi staf medis dan administrator.</p>
    </div>
</div>

{{-- Register Form --}}
<div class="ncpms-card mb-4">
    <div class="card-title-custom">
        <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-user-plus"></i></span>
        Pendaftaran Akun Baru
    </div>
    <form method="POST" action="{{ route('admin.pengguna.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label-ncpms">Nama Lengkap & Gelar</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0" style="border-color: var(--color-border);"><i class="fas fa-id-card text-muted"></i></span>
                    <input name="nama_lengkap" class="form-control form-control-ncpms border-start-0 ps-1" placeholder="Contoh: dr. Budi, Sp.GK" required>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label-ncpms">Email / Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0" style="border-color: var(--color-border);"><i class="fas fa-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control form-control-ncpms border-start-0 ps-1" placeholder="email@ncpms.local" required>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label-ncpms">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0" style="border-color: var(--color-border);"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control form-control-ncpms border-start-0 ps-1" placeholder="Minimal 8 karakter" required>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label-ncpms">Peran / Otorisasi</label>
                <select name="peran" class="form-select form-control-ncpms">
                    <option value="spgk">Dokter Sp.GK</option>
                    <option value="dietisien">Dietisien (RD)</option>
                    <option value="nutrisionis">Nutrisionis</option>
                    <option value="perawat">Perawat Ruangan</option>
                    <option value="admin_ti">Administrator TI</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label-ncpms">Unit Kerja</label>
                <input name="unit_kerja" class="form-control-ncpms" value="Instalasi Gizi Klinik">
            </div>
            <div class="col-md-2">
                <label class="form-label-ncpms">Nomor SIP</label>
                <input name="nomor_sip" class="form-control-ncpms" placeholder="Opsional">
            </div>
            <div class="col-md-2">
                <label class="form-label-ncpms">Nomor STR</label>
                <input name="nomor_str" class="form-control-ncpms" placeholder="Opsional">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <div class="form-check form-switch w-100 p-3 rounded" style="background: var(--color-primary-subtle); border: 1px solid var(--color-primary-border);">
                    <input class="form-check-input" type="checkbox" role="switch" name="status_aktif" value="1" checked id="status_aktif">
                    <label class="form-check-label ms-1 fw-bold" for="status_aktif" style="color: var(--color-primary-dark); font-size: 0.85rem;">Akun Aktif</label>
                </div>
            </div>
        </div>
        <div class="mt-4 pt-3 border-top d-flex justify-content-end">
            <button class="btn-ncpms">
                <i class="fas fa-user-check me-1"></i> Daftarkan Pengguna
            </button>
        </div>
    </form>
</div>

{{-- Staff List --}}
<div class="ncpms-card mb-0">
    <div class="card-title-custom">
        <span class="card-title-icon" style="background: var(--color-primary); color: white;"><i class="fas fa-users-gear"></i></span>
        Daftar Staf Terdaftar
    </div>
    <div class="table-responsive" style="border-radius: 10px; border: 1px solid var(--color-border);">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th style="padding-left: 16px;">Pengguna</th>
                    <th>Kontak & Peran</th>
                    <th>Unit Kerja</th>
                    <th>Status</th>
                    <th class="text-end" style="padding-right: 16px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penggunas as $p)
                <tr>
                    <td style="padding-left: 16px;">
                        <div class="d-flex align-items-center gap-2">
                            <div style="width: 38px; height: 38px; border-radius: 50%; background: var(--color-primary); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1rem; flex-shrink: 0;">
                                {{ substr($p->nama_lengkap, 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $p->nama_lengkap }}</div>
                                @if($p->nomor_sip)
                                <div class="text-muted" style="font-size: 0.75rem;"><i class="fas fa-id-badge me-1"></i>{{ $p->nomor_sip }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-dark" style="font-size: 0.88rem;">{{ $p->email }}</div>
                        <span class="badge-pill mt-1 d-inline-block" style="background: var(--color-primary-subtle); color: var(--color-primary-dark); border: 1px solid var(--color-primary-border);">
                            <i class="fas fa-shield-alt me-1"></i>{{ $p->nama_peran }}
                        </span>
                    </td>
                    <td style="color: var(--color-text-muted);">{{ $p->unit_kerja }}</td>
                    <td>
                        @if($p->trashed() || !$p->status_aktif)
                            <span class="badge-pill badge-soft-danger">
                                <i class="fas fa-ban me-1"></i>Nonaktif
                            </span>
                        @else
                            <span class="badge-pill badge-soft-success">
                                <i class="fas fa-check-circle me-1"></i>Aktif
                            </span>
                        @endif
                    </td>
                    <td class="text-end" style="padding-right: 16px;">
                        @unless($p->trashed())
                        <form method="POST" action="{{ route('admin.pengguna.destroy', $p) }}" data-confirm-delete class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn-danger-ncpms btn-sm-ncpms">
                                <i class="fas fa-user-slash me-1"></i>Nonaktifkan
                            </button>
                        </form>
                        @endunless
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-5 text-muted"><i class="fas fa-inbox fa-2x mb-2 d-block opacity-25"></i>Belum ada data pengguna.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $penggunas->links('pagination::bootstrap-5') }}</div>
</div>

@endsection
