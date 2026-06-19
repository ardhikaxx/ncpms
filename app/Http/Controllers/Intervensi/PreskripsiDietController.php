<?php

namespace App\Http\Controllers\Intervensi;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\PreskripsiDiet;
use App\Services\KalkulasiKaloriService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreskripsiDietController extends Controller
{
    public function __construct(private KalkulasiKaloriService $service) {}

    public function index()
    {
        $kunjungans = Kunjungan::with(['pasien', 'antropometri', 'preskripsiDiets'])
            ->where('status', 'dalam_pelayanan')
            ->latest('tanggal_kunjungan')
            ->get();
        $preskripsis = PreskripsiDiet::with(['kunjungan.pasien', 'pembuat', 'penyetuju'])->latest()->paginate(10);

        return view('intervensi.preskripsi', compact('kunjungans', 'preskripsis'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kunjungan_id' => ['required', 'exists:kunjungans,id'],
            'formula_basal' => ['required', 'in:harris_benedict,mifflin_st_jeor,who,konsensus_dm,konsensus_ckd'],
            'berat_badan_acuan' => ['required', 'in:aktual,ideal,adjusted'],
            'kebutuhan_energi_basal_kkal' => ['required', 'numeric', 'min:1'],
            'faktor_aktivitas' => ['required', 'numeric', 'between:1.2,1.9'],
            'faktor_stres' => ['required', 'numeric', 'between:1.0,2.0'],
            'persen_karbohidrat' => ['required', 'numeric', 'between:0,100'],
            'persen_protein' => ['required', 'numeric', 'between:0,100'],
            'persen_lemak' => ['required', 'numeric', 'between:0,100'],
            'gram_serat' => ['nullable', 'numeric'],
            'batas_natrium_mg' => ['nullable', 'numeric'],
            'batas_kalium_mg' => ['nullable', 'numeric'],
            'batas_fosfor_mg' => ['nullable', 'numeric'],
            'batas_cairan_ml' => ['nullable', 'numeric'],
            'bentuk_makanan' => ['required', 'in:biasa,lunak,saring,cair_penuh,cair_jernih,formula_medis'],
            'frekuensi_makan_utama' => ['required', 'integer', 'between:1,6'],
            'frekuensi_selingan' => ['required', 'integer', 'between:0,6'],
            'catatan_klinis' => ['nullable'],
            'tujuan_terapi' => ['required'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_evaluasi' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'durasi_hari' => ['nullable', 'integer', 'min:1'],
        ], $this->messages());

        $totalPersen = (float) $data['persen_karbohidrat'] + (float) $data['persen_protein'] + (float) $data['persen_lemak'];
        if (round($totalPersen, 2) !== 100.0) {
            return back()->withErrors(['persen_karbohidrat' => 'Total persentase karbohidrat, protein, dan lemak wajib 100%.'])->withInput();
        }

        $kunjungan = Kunjungan::findOrFail($data['kunjungan_id']);
        abort_if($kunjungan->dokumen_terkunci, 423, 'Dokumen klinis sudah terkunci.');

        $total = $this->service->hitungTotalKebutuhan($data['kebutuhan_energi_basal_kkal'], $data['faktor_aktivitas'], $data['faktor_stres']);
        $makro = $this->service->distribusiMakro($total, $data['persen_karbohidrat'], $data['persen_protein'], $data['persen_lemak']);

        PreskripsiDiet::create(array_merge($data, $makro, [
            'total_kebutuhan_energi_kkal' => $total,
            'pantangan_spesifik' => array_values(array_filter(explode(',', (string) $request->pantangan_spesifik))),
            'target_luaran_klinis' => array_values(array_filter(explode(',', (string) $request->target_luaran_klinis))),
            'status' => 'aktif',
            'dibuat_oleh' => Auth::id(),
        ]));

        return back()->with('swal_success', 'Preskripsi diet berhasil disimpan.');
    }

    public function setujui(PreskripsiDiet $preskripsiDiet)
    {
        abort_unless(Auth::user()->peran === 'spgk', 403, 'Hanya SpGK yang dapat menyetujui preskripsi diet.');
        $preskripsiDiet->update([
            'disetujui_oleh' => Auth::id(),
            'disetujui_pada' => now(),
        ]);

        return back()->with('swal_success', 'Preskripsi diet berhasil disetujui SpGK.');
    }

    private function messages()
    {
        return [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'integer' => ':attribute harus berupa bilangan bulat.',
            'min' => ':attribute minimal :min.',
            'between' => ':attribute harus antara :min dan :max.',
            'in' => ':attribute tidak sesuai pilihan yang tersedia.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'after_or_equal' => ':attribute harus setelah atau sama dengan tanggal mulai.',
            'exists' => ':attribute tidak ditemukan.',
        ];
    }
}
