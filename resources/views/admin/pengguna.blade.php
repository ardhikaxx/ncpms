@extends('layouts.app')
@section('title','Manajemen Pengguna')
@section('breadcrumb','Admin / Pengguna')
@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen Akun & Hak Akses</h1>
        <p class="page-subtitle">Kontrol penuh atas otorisasi staf medis dan administrator.</p>
    </div>
</div>

<div class="ncpms-card">
    <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-user-plus"></i></span> Pendaftaran Akun Baru</h2>
    <form method="POST" action="{{ route('admin.pengguna.store') }}">
        @csrf
        <div class="row g-4">
            <div class="col-md-4">
                <label class="form-label-ncpms">Nama Lengkap & Gelar</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-id-card text-muted"></i></span>
                    <input name="nama_lengkap" class="form-control-ncpms border-start-0 ps-0" placeholder="Contoh: dr. Budi, Sp.GK" required>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label-ncpms">Email / Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                    <input type="email" name="email" class="form-control-ncpms border-start-0 ps-0" placeholder="email@ncpms.local" required>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label-ncpms">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                    <input type="password" name="password" class="form-control-ncpms border-start-0 ps-0" placeholder="Minimal 8 karakter" required>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label-ncpms">Peran / Otorisasi</label>
                <select name="peran" class="form-control-ncpms">
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
                <div class="form-check form-switch mt-2 w-100 p-2 rounded" style="background: rgba(26,122,100,0.05); border: 1px solid rgba(26,122,100,0.1);">
                    <input class="form-check-input" type="checkbox" role="switch" name="status_aktif" value="1" checked id="status_aktif">
                    <label class="form-check-label ms-2 fw-bold text-primary-dark" for="status_aktif">Akun Aktif</label>
                </div>
            </div>
        </div>
        <div class="mt-4 pt-4 border-top d-flex justify-content-end">
            <button class="btn-primary-ncpms"><i class="fas fa-user-check me-1"></i> Daftarkan Pengguna</button>
        </div>
    </form>
</div>

<div class="ncpms-card">
    <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-users-gear"></i></span> Daftar Staf Terdaftar</h2>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Pengguna</th>
                    <th>Kontak & Peran</th>
                    <th>Unit Kerja</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($penggunas as $p)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="user-avatar-sm" style="width: 40px; height: 40px; border-radius: 50%; background-color: var(--color-primary), var(--color-primary-dark)); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">
                                {{ substr($p->nama_lengkap, 0, 1) }}
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $p->nama_lengkap }}</div>
                                <div class="text-muted small">
                                    @if($p->nomor_sip) <span class="me-2"><i class="fas fa-id-badge"></i> {{ $p->nomor_sip }}</span> @endif
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-dark">{{ $p->email }}</div>
                        <span class="badge" style="background: var(--color-primary-subtle); color: var(--color-primary-dark); font-weight: 700; padding: 4px 8px; border-radius: 6px;"><i class="fas fa-shield-alt"></i> {{ $p->nama_peran }}</span>
                    </td>
                    <td class="text-muted">{{ $p->unit_kerja }}</td>
                    <td>
                        @if($p->trashed() || !$p->status_aktif)
                            <span class="badge" style="background: #fff5f5; color: var(--color-primary); border: 1px solid #ffe3e3; padding: 6px 10px; border-radius: 20px;"><i class="fas fa-ban"></i> Nonaktif</span>
                        @else
                            <span class="badge" style="background: #ebfbee; color: var(--color-primary); border: 1px solid #d3f9d8; padding: 6px 10px; border-radius: 20px;"><i class="fas fa-check-circle"></i> Aktif</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @unless($p->trashed())
                            <form method="POST" action="{{ route('admin.pengguna.destroy', $p) }}" data-confirm-delete class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn-danger-ncpms btn-sm-ncpms" title="Nonaktifkan Akun" style="border-radius: 8px; padding: 8px 12px;"><i class="fas fa-user-slash"></i></button>
                            </form>
                        @endunless
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-5 text-muted"><i class="fas fa-inbox fa-3x mb-3 opacity-25"></i><br>Belum ada data pengguna.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $penggunas->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
