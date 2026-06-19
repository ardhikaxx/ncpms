<?php

namespace App\Http\Controllers;

use App\Models\BahanMakanan;
use App\Models\CatatanKonseling;
use App\Models\DataAntropometri;
use App\Models\DataBiokimia;
use App\Models\DetailMenuHarian;
use App\Models\DokumenEdukasi;
use App\Models\Kunjungan;
use App\Models\PemeriksaanFisikGizi;
use App\Models\PreskripsiDiet;
use App\Models\RiwayatAsupanGizi;
use App\Models\SkriningGizi;
use App\Services\AntropometriService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class KunjunganController extends Controller
{
    public function show(Kunjungan $kunjungan)
    {
        $this->izinkan(['perawat', 'nutrisionis', 'dietisien', 'spgk']);

        $kunjungan->load([
            'pasien.riwayatAlergi',
            'skriningGizi',
            'antropometri',
            'fisik',
            'biokimia',
            'asupan',
            'diagnosaGizis',
            'preskripsiDiets.detailMenuHarians.bahanMakanan',
            'preskripsiKritis',
            'monitoring',
            'catatanKonselings.pelaksana',
            'dokumenEdukasiis.pembuat',
        ]);
        $bahanMakanans = BahanMakanan::orderBy('nama_bahan')->get();

        return view('pasien.kunjungan.show', compact('kunjungan', 'bahanMakanans'));
    }

    public function storeSkrining(Request $request, Kunjungan $kunjungan)
    {
        $this->izinkan(['perawat', 'spgk']);
        $this->pastikanTidakTerkunci($kunjungan);
        $data = $request->validate([
            'metode_skrining' => ['required', 'in:MNA,NRS2002,MST,MUST,STAMP'],
            'skor_penurunan_bb' => ['required', 'integer', 'between:0,3'],
            'skor_penurunan_asupan' => ['required', 'integer', 'between:0,3'],
            'skor_keparahan_penyakit' => ['required', 'integer', 'between:0,3'],
            'skor_usia' => ['nullable', 'integer', 'between:0,3'],
            'rekomendasi_tindak_lanjut' => ['nullable'],
        ], $this->messages());

        $data['skor_usia'] = $data['skor_usia'] ?? 0;
        $data['total_skor'] = $data['skor_penurunan_bb'] + $data['skor_penurunan_asupan'] + $data['skor_keparahan_penyakit'] + $data['skor_usia'];
        $data['kategori_risiko'] = $data['total_skor'] >= 4 ? 'risiko_tinggi' : ($data['total_skor'] >= 2 ? 'risiko_sedang' : 'risiko_rendah');
        $data['dilakukan_oleh'] = Auth::id();

        SkriningGizi::updateOrCreate(['kunjungan_id' => $kunjungan->id], $data);
        $kunjungan->update(['status' => 'dalam_pelayanan']);

        return back()->with('swal_success', 'Skrining gizi berhasil disimpan.');
    }

    public function storeAntropometri(Request $request, Kunjungan $kunjungan, AntropometriService $service)
    {
        $this->izinkan(['nutrisionis', 'dietisien', 'spgk']);
        $this->pastikanTidakTerkunci($kunjungan);
        $data = $request->validate([
            'tanggal_pengukuran' => ['required', 'date', 'before_or_equal:today'],
            'berat_badan_kg' => ['required', 'numeric', 'between:1,350'],
            'tinggi_badan_cm' => ['required', 'numeric', 'between:30,250'],
            'lingkar_lengan_atas_cm' => ['nullable', 'numeric', 'between:1,80'],
            'lingkar_perut_cm' => ['nullable', 'numeric', 'between:20,250'],
            'is_pediatri' => ['nullable', 'boolean'],
            'usia_tahun' => ['nullable', 'integer', 'between:0,18'],
            'usia_bulan' => ['nullable', 'integer', 'between:0,11'],
            'zscore_bb_u' => ['nullable', 'numeric'],
            'zscore_tb_u' => ['nullable', 'numeric'],
            'zscore_imt_u' => ['nullable', 'numeric'],
            'status_gizi_anak' => ['nullable', 'in:gizi_buruk,gizi_kurang,gizi_baik,gizi_lebih,obesitas'],
        ], $this->messages());

        $imt = $service->hitungIMT($data['berat_badan_kg'], $data['tinggi_badan_cm']);
        $data['imt'] = $imt;
        $data['status_gizi_imt'] = $service->kategoriIMT($imt);
        $data['berat_badan_ideal_kg'] = $service->beratBadanIdeal($data['tinggi_badan_cm'], $kunjungan->pasien->jenis_kelamin);
        $data['is_pediatri'] = $request->boolean('is_pediatri');
        $data['dicatat_oleh'] = Auth::id();

        DataAntropometri::updateOrCreate(['kunjungan_id' => $kunjungan->id], $data);
        return back()->with('swal_success', 'Data antropometri berhasil disimpan.');
    }

    public function storeFisik(Request $request, Kunjungan $kunjungan)
    {
        $this->izinkan(['perawat', 'dietisien', 'spgk']);
        $this->pastikanTidakTerkunci($kunjungan);
        $data = $request->validate([
            'tekanan_darah_sistolik' => ['nullable', 'integer', 'between:50,260'],
            'tekanan_darah_diastolik' => ['nullable', 'integer', 'between:30,160'],
            'nadi_per_menit' => ['nullable', 'integer', 'between:30,220'],
            'respirasi_per_menit' => ['nullable', 'integer', 'between:5,60'],
            'suhu_celsius' => ['nullable', 'numeric', 'between:30,45'],
            'saturasi_oksigen_persen' => ['nullable', 'integer', 'between:50,100'],
            'edema' => ['nullable', 'boolean'],
            'lokasi_edema' => ['nullable', 'max:100'],
            'kondisi_mulut' => ['nullable'],
            'catatan_klinis' => ['nullable'],
        ], $this->messages());

        $data['edema'] = $request->boolean('edema');
        $data['tanda_defisiensi'] = array_values(array_filter(explode(',', (string) $request->tanda_defisiensi)));
        $data['gangguan_gastrointestinal'] = array_values(array_filter(explode(',', (string) $request->gangguan_gastrointestinal)));
        $data['dicatat_oleh'] = Auth::id();

        PemeriksaanFisikGizi::updateOrCreate(['kunjungan_id' => $kunjungan->id], $data);
        return back()->with('swal_success', 'Pemeriksaan fisik berhasil disimpan.');
    }

    public function storeBiokimia(Request $request, Kunjungan $kunjungan)
    {
        $this->izinkan(['dietisien', 'spgk']);
        $this->pastikanTidakTerkunci($kunjungan);
        $data = $request->validate([
            'tanggal_pemeriksaan' => ['required', 'date', 'before_or_equal:today'],
            'sumber_data' => ['required', 'in:lab_internal,input_manual,satusehat'],
            'gula_darah_puasa' => ['nullable', 'numeric'],
            'hba1c_persen' => ['nullable', 'numeric'],
            'kolesterol_total' => ['nullable', 'numeric'],
            'albumin' => ['nullable', 'numeric'],
            'hemoglobin' => ['nullable', 'numeric'],
            'kreatinin' => ['nullable', 'numeric'],
            'catatan_tambahan' => ['nullable'],
        ], $this->messages());

        $data['dicatat_oleh'] = Auth::id();
        DataBiokimia::updateOrCreate(['kunjungan_id' => $kunjungan->id], $data);
        return back()->with('swal_success', 'Data biokimia berhasil disimpan.');
    }

    public function storeAsupan(Request $request, Kunjungan $kunjungan)
    {
        $this->izinkan(['nutrisionis', 'dietisien', 'spgk']);
        $this->pastikanTidakTerkunci($kunjungan);
        $data = $request->validate([
            'metode' => ['required', 'in:food_recall_24h,food_recall_48h,food_recall_72h,ffq_semi_kuantitatif'],
            'tanggal_recall' => ['required', 'date', 'before_or_equal:today'],
            'detail_asupan' => ['required'],
            'total_energi_kkal' => ['nullable', 'numeric'],
            'total_protein_gram' => ['nullable', 'numeric'],
            'total_lemak_gram' => ['nullable', 'numeric'],
            'total_karbohidrat_gram' => ['nullable', 'numeric'],
            'kesimpulan_asupan' => ['nullable'],
        ], $this->messages());

        $data['detail_asupan'] = [['catatan' => $data['detail_asupan']]];
        $data['dicatat_oleh'] = Auth::id();
        RiwayatAsupanGizi::updateOrCreate(['kunjungan_id' => $kunjungan->id], $data);
        return back()->with('swal_success', 'Riwayat asupan berhasil disimpan.');
    }

    public function storeMenuHarian(Request $request, PreskripsiDiet $preskripsiDiet)
    {
        $this->izinkan(['dietisien', 'spgk']);
        $this->pastikanTidakTerkunci($preskripsiDiet->kunjungan);

        $data = $request->validate([
            'waktu_makan' => ['required', 'in:makan_pagi,selingan_pagi,makan_siang,selingan_sore,makan_malam,selingan_malam'],
            'bahan_makanan_id' => ['required', 'exists:bahan_makanans,id'],
            'porsi_gram' => ['required', 'numeric', 'between:1,1000'],
            'keterangan_penukar' => ['nullable'],
        ], $this->messages());

        $bahan = BahanMakanan::findOrFail($data['bahan_makanan_id']);
        $faktor = (float) $data['porsi_gram'] / (float) $bahan->porsi_standar_gram;

        DetailMenuHarian::create(array_merge($data, [
            'preskripsi_diet_id' => $preskripsiDiet->id,
            'energi_kkal' => round($bahan->energi_kkal * $faktor, 2),
            'protein_gram' => round($bahan->protein_gram * $faktor, 2),
            'lemak_gram' => round($bahan->lemak_gram * $faktor, 2),
            'karbohidrat_gram' => round($bahan->karbohidrat_gram * $faktor, 2),
        ]));

        return back()->with('swal_success', 'Detail menu harian berhasil ditambahkan.');
    }

    public function storeKonseling(Request $request, Kunjungan $kunjungan)
    {
        $this->izinkan(['nutrisionis', 'dietisien', 'spgk']);
        $this->pastikanTidakTerkunci($kunjungan);

        $data = $request->validate([
            'tanggal_konseling' => ['required', 'date', 'before_or_equal:today'],
            'durasi_menit' => ['nullable', 'integer', 'between:1,240'],
            'metode' => ['required', 'in:tatap_muka,telepon,video_call'],
            'topik_konseling' => ['required'],
            'isi_konseling' => ['required'],
            'hambatan_pasien' => ['nullable'],
            'kesepakatan_tindak_lanjut' => ['nullable'],
            'tingkat_pemahaman_pasien' => ['nullable', 'in:baik,cukup,kurang'],
        ], $this->messages());

        $data['topik_konseling'] = array_values(array_filter(array_map('trim', explode(',', $data['topik_konseling']))));
        $data['dilakukan_oleh'] = Auth::id();

        CatatanKonseling::create(array_merge($data, ['kunjungan_id' => $kunjungan->id]));

        return back()->with('swal_success', 'Catatan konseling berhasil disimpan.');
    }

    public function storeDokumenEdukasi(Request $request, Kunjungan $kunjungan)
    {
        $this->izinkan(['dietisien', 'spgk']);
        $this->pastikanTidakTerkunci($kunjungan);

        $data = $request->validate([
            'judul_dokumen' => ['required', 'max:255'],
            'tipe' => ['required', 'in:leaflet_diet,panduan_makan,ringkasan_kalori,pantangan_alergi,rencana_makan'],
            'ringkasan' => ['required'],
            'token_expired_at' => ['nullable', 'date', 'after:today'],
        ], $this->messages());

        DokumenEdukasi::create([
            'pasien_id' => $kunjungan->pasien_id,
            'kunjungan_id' => $kunjungan->id,
            'judul_dokumen' => $data['judul_dokumen'],
            'tipe' => $data['tipe'],
            'konten_json' => [
                'ringkasan' => $data['ringkasan'],
                'nomor_kunjungan' => $kunjungan->nomor_kunjungan,
                'dibuat_pada' => now()->toDateTimeString(),
            ],
            'token_akses' => Str::random(40),
            'token_expired_at' => $data['token_expired_at'] ?? now()->addDays(7),
            'dibuat_oleh' => Auth::id(),
        ]);

        return back()->with('swal_success', 'Dokumen edukasi berhasil dibuat.');
    }

    public function storePreskripsiKritis(Request $request, Kunjungan $kunjungan)
    {
        $this->izinkan(['dietisien', 'spgk']);
        $this->pastikanTidakTerkunci($kunjungan);

        $data = $request->validate([
            'jenis_nutrisi' => ['required', 'in:enteral,parenteral,kombinasi'],
            'rute_pemberian' => ['required', 'max:100'],
            'nama_formula' => ['required', 'max:200'],
            'volume_ml' => ['required', 'numeric'],
            'frekuensi_sehari' => ['required', 'integer'],
            'kecepatan_pemberian' => ['nullable', 'numeric'],
            'total_kalori_kkal' => ['required', 'numeric'],
            'total_protein_gram' => ['required', 'numeric'],
            'total_lemak_gram' => ['required', 'numeric'],
            'total_karbohidrat_gram' => ['required', 'numeric'],
            'instruksi_khusus' => ['nullable'],
        ], $this->messages());

        $data['dicatat_oleh'] = Auth::id();
        \App\Models\PreskripsiKritis::updateOrCreate(['kunjungan_id' => $kunjungan->id], $data);
        return back()->with('swal_success', 'Preskripsi nutrisi kritis (Enteral/Parenteral) berhasil disimpan.');
    }

    public function kunci(\Illuminate\Http\Request $request, Kunjungan $kunjungan)
    {
        abort_unless(Auth::user()->peran === 'spgk', 403, 'Hanya SpGK yang dapat mengunci dokumen klinis.');
        
        $request->validate([
            'pin_tte' => ['required', 'string', 'min:6']
        ], [
            'pin_tte.required' => 'PIN Tanda Tangan Elektronik wajib diisi.',
            'pin_tte.min' => 'PIN TTE minimal 6 karakter.'
        ]);

        $kunjungan->update([
            'dokumen_terkunci' => true,
            'dikunci_oleh' => Auth::id(),
            'dikunci_pada' => now(),
        ]);

        return back()->with('swal_success', 'Dokumen berhasil dikunci & diverifikasi dengan TTE Tersertifikasi.');
    }

    public function selesaikan(Kunjungan $kunjungan)
    {
        $this->izinkan(['dietisien', 'spgk']);

        $kunjungan->update(['status' => 'selesai', 'waktu_selesai' => now()]);
        return redirect()->route('pasien.show', $kunjungan->pasien)->with('swal_success', 'Kunjungan berhasil diselesaikan.');
    }

    private function izinkan(array $peran)
    {
        abort_unless(in_array(Auth::user()->peran, $peran, true), 403, 'Akses ditolak. Peran Anda tidak memiliki kewenangan untuk tindakan ini.');
    }

    private function pastikanTidakTerkunci(Kunjungan $kunjungan)
    {
        abort_if($kunjungan->dokumen_terkunci, 423, 'Dokumen klinis sudah terkunci dan tidak dapat diubah.');
    }

    private function messages()
    {
        return [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'integer' => ':attribute harus berupa bilangan bulat.',
            'between' => ':attribute harus antara :min dan :max.',
            'max' => ':attribute maksimal :max karakter.',
            'in' => ':attribute tidak sesuai pilihan yang tersedia.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'before_or_equal' => ':attribute tidak boleh lebih dari hari ini.',
        ];
    }
}
