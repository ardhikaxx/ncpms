@extends('layouts.app')
@section('title','Manajemen Pengguna')
@section('breadcrumb','Admin / Pengguna')
@section('content')
<div class="page-header"><div><h1 class="page-title">Manajemen Pengguna</h1><p class="page-subtitle">Admin TI hanya mengelola akun, tidak membuka data klinis pasien.</p></div></div>
<div class="ncpms-card">
    <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-user-plus"></i></span> Tambah Akun</h2>
    <form method="POST" action="{{ route('admin.pengguna.store') }}" class="row g-3">
        @csrf
        <div class="col-md-3"><label class="form-label-ncpms">Nama Lengkap</label><input name="nama_lengkap" class="form-control-ncpms" required></div>
        <div class="col-md-3"><label class="form-label-ncpms">Email</label><input type="email" name="email" class="form-control-ncpms" required></div>
        <div class="col-md-2"><label class="form-label-ncpms">Password</label><input type="password" name="password" class="form-control-ncpms" required></div>
        <div class="col-md-2"><label class="form-label-ncpms">Peran</label><select name="peran" class="form-control-ncpms"><option value="spgk">SpGK</option><option value="dietisien">Dietisien</option><option value="nutrisionis">Nutrisionis</option><option value="perawat">Perawat</option><option value="admin_ti">Admin TI</option></select></div>
        <div class="col-md-2"><label class="form-label-ncpms">Unit Kerja</label><input name="unit_kerja" class="form-control-ncpms" value="Poliklinik Gizi"></div>
        <div class="col-md-2"><label class="form-label-ncpms">SIP</label><input name="nomor_sip" class="form-control-ncpms"></div>
        <div class="col-md-2"><label class="form-label-ncpms">STR</label><input name="nomor_str" class="form-control-ncpms"></div>
        <div class="col-md-2 form-check d-flex align-items-end gap-2 ms-2"><input type="checkbox" class="form-check-input" name="status_aktif" value="1" checked id="status_aktif"><label class="form-check-label" for="status_aktif">Aktif</label></div>
        <div class="col-12"><button class="btn-primary-ncpms"><i class="fas fa-save"></i> Simpan Akun</button></div>
    </form>
</div>
<div class="ncpms-card">
    <h2 class="card-title-custom"><span class="card-title-icon"><i class="fas fa-users-gear"></i></span> Daftar Akun</h2>
    <div class="table-responsive"><table class="table align-middle"><thead><tr><th>Nama</th><th>Email</th><th>Peran</th><th>Unit</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
    @foreach($penggunas as $p)
        <tr><td>{{ $p->nama_lengkap }}</td><td>{{ $p->email }}</td><td>{{ $p->nama_peran }}</td><td>{{ $p->unit_kerja }}</td><td>{{ $p->trashed() ? 'Nonaktif' : ($p->status_aktif ? 'Aktif' : 'Nonaktif') }}</td><td>
            @unless($p->trashed())
                <form method="POST" action="{{ route('admin.pengguna.destroy', $p) }}" data-confirm-delete class="d-inline">@csrf @method('DELETE')<button class="btn-danger-ncpms btn-sm-ncpms"><i class="fas fa-user-slash"></i></button></form>
            @endunless
        </td></tr>
    @endforeach
    </tbody></table></div>{{ $penggunas->links('pagination::bootstrap-5') }}
</div>
@endsection
