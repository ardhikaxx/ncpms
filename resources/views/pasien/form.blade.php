<div class="row g-3 mb-4">
    <div class="col-md-4">
        <label class="form-label-ncpms">Nomor Rekam Medis <span class="required-mark">*</span></label>
        <input name="nomor_rekam_medis" class="form-control-ncpms" required value="{{ old('nomor_rekam_medis', $pasien->nomor_rekam_medis ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label-ncpms">NIK</label>
        <input name="nik" class="form-control-ncpms" value="{{ old('nik', $pasien->nik ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label-ncpms">Nama Lengkap <span class="required-mark">*</span></label>
        <input name="nama_lengkap" class="form-control-ncpms" required value="{{ old('nama_lengkap', $pasien->nama_lengkap ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label-ncpms">Tempat Lahir</label>
        <input name="tempat_lahir" class="form-control-ncpms" value="{{ old('tempat_lahir', $pasien->tempat_lahir ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label-ncpms">Tanggal Lahir <span class="required-mark">*</span></label>
        <input type="date" name="tanggal_lahir" class="form-control-ncpms" required value="{{ old('tanggal_lahir', isset($pasien) ? $pasien->tanggal_lahir?->format('Y-m-d') : '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label-ncpms">Jenis Kelamin</label>
        <select name="jenis_kelamin" class="form-control-ncpms">
            <option value="L" @selected(old('jenis_kelamin', $pasien->jenis_kelamin ?? '')==='L')>Laki-laki</option>
            <option value="P" @selected(old('jenis_kelamin', $pasien->jenis_kelamin ?? '')==='P')>Perempuan</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label-ncpms">Golongan Darah</label>
        <select name="golongan_darah" class="form-control-ncpms">
            <option value="tidak_diketahui">Tidak diketahui</option>
            @foreach(['A','B','AB','O'] as $gd)
            <option value="{{ $gd }}" @selected(old('golongan_darah', $pasien->golongan_darah ?? '')===$gd)>{{ $gd }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label-ncpms">Nomor Telepon</label>
        <input name="nomor_telepon" class="form-control-ncpms" value="{{ old('nomor_telepon', $pasien->nomor_telepon ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label-ncpms">Nomor BPJS</label>
        <input name="nomor_bpjs" class="form-control-ncpms" value="{{ old('nomor_bpjs', $pasien->nomor_bpjs ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label-ncpms">Status</label>
        <select name="status_aktif" class="form-control-ncpms">
            <option value="1">Aktif</option>
            <option value="0" @selected(old('status_aktif', $pasien->status_aktif ?? 1)==0)>Nonaktif</option>
        </select>
    </div>
    <div class="col-12">
        <label class="form-label-ncpms">Alamat</label>
        <textarea name="alamat" class="form-control-ncpms" rows="2">{{ old('alamat', $pasien->alamat ?? '') }}</textarea>
    </div>
</div>
@if(!$pasien)
<div class="section-divider"></div>
<h2 class="card-title-custom">Alergi Awal</h2>
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <label class="form-label-ncpms">Jenis Alergi</label>
        <select name="jenis_alergi" class="form-control-ncpms">
            <option value="makanan">Makanan</option>
            <option value="obat">Obat</option>
            <option value="lingkungan">Lingkungan</option>
            <option value="lainnya">Lainnya</option>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label-ncpms">Nama Alergen</label>
        <input name="nama_alergen" class="form-control-ncpms">
    </div>
    <div class="col-md-3">
        <label class="form-label-ncpms">Reaksi</label>
        <input name="reaksi" class="form-control-ncpms">
    </div>
    <div class="col-md-3">
        <label class="form-label-ncpms">Keparahan</label>
        <select name="tingkat_keparahan" class="form-control-ncpms">
            <option value="ringan">Ringan</option>
            <option value="sedang">Sedang</option>
            <option value="berat">Berat</option>
        </select>
    </div>
</div>
@endif
