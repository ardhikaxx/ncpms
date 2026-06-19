@extends('layouts.app')
@section('title','Master Database DKPI')
@section('breadcrumb','Master Data / Bahan Makanan')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fas fa-carrot me-2" style="color: var(--color-primary);"></i>Database Komposisi Pangan (DKPI)</h1>
        <p class="page-subtitle">Kelola master data bahan makanan, nilai gizi, dan porsi standar untuk kalkulasi diet.</p>
    </div>
    <button class="btn-ncpms" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="fas fa-plus"></i> Tambah Bahan
    </button>
</div>

<div class="ncpms-card">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form method="GET" action="{{ route('bahan-makanan.index') }}" class="d-flex gap-2 w-50">
            <input type="text" name="search" class="form-control-ncpms w-100" placeholder="Cari nama bahan makanan atau kategori..." value="{{ request('search') }}">
            <button class="btn-ncpms-outline"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <div class="table-responsive" style="border-radius: var(--radius-sm); border: 1px solid var(--color-border);">
        <table class="table data-table mb-0">
            <thead>
                <tr>
                    <th>Nama Bahan</th>
                    <th>Kategori</th>
                    <th>Porsi (g)</th>
                    <th>Energi (kkal)</th>
                    <th>Protein (g)</th>
                    <th>Lemak (g)</th>
                    <th>KH (g)</th>
                    <th>Sumber</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bahanMakanans as $b)
                <tr>
                    <td class="fw-bold">{{ $b->nama_bahan }}</td>
                    <td><span class="badge-pill badge-soft-primary">{{ str_replace('_',' ', $b->kategori) }}</span></td>
                    <td>{{ $b->porsi_standar_gram }}</td>
                    <td>{{ $b->energi_kkal }}</td>
                    <td>{{ $b->protein_gram }}</td>
                    <td>{{ $b->lemak_gram }}</td>
                    <td>{{ $b->karbohidrat_gram }}</td>
                    <td class="text-muted" style="font-size: 0.8rem;">{{ $b->sumber_data ?? 'DKPI' }}</td>
                    <td class="text-end">
                        <button class="btn-sm-ncpms btn-ncpms-outline" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $b->id }}">Edit</button>
                        <form method="POST" action="{{ route('bahan-makanan.destroy', $b) }}" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn-sm-ncpms btn-danger-ncpms">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="text-center py-4 text-muted">Belum ada data bahan makanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $bahanMakanans->links('pagination::bootstrap-5') }}</div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('bahan-makanan.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold" style="color: var(--color-primary-dark);">Tambah Bahan Makanan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-8"><label class="form-label-ncpms">Nama Bahan <span class="required-mark">*</span></label><input name="nama_bahan" class="form-control-ncpms" required></div>
                <div class="col-md-4"><label class="form-label-ncpms">Kategori <span class="required-mark">*</span></label><select name="kategori" class="form-control-ncpms" required><option value="karbohidrat">Karbohidrat</option><option value="protein_hewani">Protein Hewani</option><option value="protein_nabati">Protein Nabati</option><option value="sayuran">Sayuran</option><option value="buah">Buah</option><option value="lemak">Lemak/Minyak</option><option value="minuman">Minuman</option><option value="bumbu">Bumbu</option><option value="lainnya">Lainnya</option></select></div>
                <div class="col-md-4"><label class="form-label-ncpms">Porsi Standar (gram) <span class="required-mark">*</span></label><input type="number" step="0.1" name="porsi_standar_gram" class="form-control-ncpms" value="100" required></div>
                <div class="col-md-4"><label class="form-label-ncpms">Energi (kkal) <span class="required-mark">*</span></label><input type="number" step="0.1" name="energi_kkal" class="form-control-ncpms" required></div>
                <div class="col-md-4"><label class="form-label-ncpms">Sumber Data</label><input name="sumber_data" class="form-control-ncpms" value="DKPI"></div>
                <div class="col-md-4"><label class="form-label-ncpms">Protein (g) <span class="required-mark">*</span></label><input type="number" step="0.1" name="protein_gram" class="form-control-ncpms" required></div>
                <div class="col-md-4"><label class="form-label-ncpms">Lemak (g) <span class="required-mark">*</span></label><input type="number" step="0.1" name="lemak_gram" class="form-control-ncpms" required></div>
                <div class="col-md-4"><label class="form-label-ncpms">Karbohidrat (g) <span class="required-mark">*</span></label><input type="number" step="0.1" name="karbohidrat_gram" class="form-control-ncpms" required></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn-ncpms">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

{{-- Modals Edit --}}
@foreach($bahanMakanans as $b)
<div class="modal fade" id="modalEdit{{ $b->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" action="{{ route('bahan-makanan.update', $b) }}" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title fw-bold" style="color: var(--color-primary-dark);">Edit Bahan Makanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-md-8"><label class="form-label-ncpms">Nama Bahan <span class="required-mark">*</span></label><input name="nama_bahan" class="form-control-ncpms" value="{{ $b->nama_bahan }}" required></div>
                <div class="col-md-4"><label class="form-label-ncpms">Kategori <span class="required-mark">*</span></label>
                    <select name="kategori" class="form-control-ncpms" required>
                        @foreach(['karbohidrat','protein_hewani','protein_nabati','sayuran','buah','lemak','minuman','bumbu','lainnya'] as $kat)
                        <option value="{{ $kat }}" {{ $b->kategori==$kat?'selected':'' }}>{{ str_replace('_',' ', $kat) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4"><label class="form-label-ncpms">Porsi Standar (gram) <span class="required-mark">*</span></label><input type="number" step="0.1" name="porsi_standar_gram" class="form-control-ncpms" value="{{ $b->porsi_standar_gram }}" required></div>
                <div class="col-md-4"><label class="form-label-ncpms">Energi (kkal) <span class="required-mark">*</span></label><input type="number" step="0.1" name="energi_kkal" class="form-control-ncpms" value="{{ $b->energi_kkal }}" required></div>
                <div class="col-md-4"><label class="form-label-ncpms">Sumber Data</label><input name="sumber_data" class="form-control-ncpms" value="{{ $b->sumber_data }}"></div>
                <div class="col-md-4"><label class="form-label-ncpms">Protein (g) <span class="required-mark">*</span></label><input type="number" step="0.1" name="protein_gram" class="form-control-ncpms" value="{{ $b->protein_gram }}" required></div>
                <div class="col-md-4"><label class="form-label-ncpms">Lemak (g) <span class="required-mark">*</span></label><input type="number" step="0.1" name="lemak_gram" class="form-control-ncpms" value="{{ $b->lemak_gram }}" required></div>
                <div class="col-md-4"><label class="form-label-ncpms">Karbohidrat (g) <span class="required-mark">*</span></label><input type="number" step="0.1" name="karbohidrat_gram" class="form-control-ncpms" value="{{ $b->karbohidrat_gram }}" required></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn-ncpms">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection
