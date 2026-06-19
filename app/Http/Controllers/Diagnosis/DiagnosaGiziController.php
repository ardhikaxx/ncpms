<?php

namespace App\Http\Controllers\Diagnosis;

use App\Http\Controllers\Controller;
use App\Models\DiagnosaGizi;
use App\Models\Kunjungan;
use App\Models\TerminologiDiagnosisGizi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiagnosaGiziController extends Controller
{
    public function index()
    {
        $kunjungans = Kunjungan::with(['pasien', 'skriningGizi', 'diagnosaGizis'])
            ->where('status', 'dalam_pelayanan')
            ->latest('tanggal_kunjungan')
            ->get();
        $terminologis = TerminologiDiagnosisGizi::where('is_aktif', true)->orderBy('kode_diagnosis')->get();
        $diagnosas = DiagnosaGizi::with(['kunjungan.pasien', 'terminologi', 'validator'])->latest()->paginate(10);

        return view('diagnosis.index', compact('kunjungans', 'terminologis', 'diagnosas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kunjungan_id' => ['required', 'exists:kunjungans,id'],
            'terminologi_id' => ['required', 'exists:terminologi_diagnosis_gizis,id'],
            'domain' => ['required', 'in:asupan,klinis,perilaku_lingkungan'],
            'etiologi_penyebab' => ['required'],
            'signs_symptoms' => ['required'],
            'urutan_prioritas' => ['required', 'integer', 'between:1,9'],
        ], $this->messages());

        $kunjungan = Kunjungan::findOrFail($data['kunjungan_id']);
        abort_if($kunjungan->dokumen_terkunci, 423, 'Dokumen klinis sudah terkunci.');

        $terminologi = TerminologiDiagnosisGizi::findOrFail($data['terminologi_id']);
        $data['problem_masalah'] = $terminologi->nama_masalah;
        $data['narasi_pes'] = $terminologi->nama_masalah.' berkaitan dengan '.$data['etiologi_penyebab'].' ditandai dengan '.$data['signs_symptoms'].'.';
        $data['status'] = 'aktif';
        $data['dicatat_oleh'] = Auth::id();

        DiagnosaGizi::create($data);
        return back()->with('swal_success', 'Diagnosis gizi format PES berhasil disimpan.');
    }

    public function validasi(DiagnosaGizi $diagnosaGizi)
    {
        abort_unless(Auth::user()->peran === 'spgk', 403, 'Hanya SpGK yang dapat memvalidasi diagnosis.');
        abort_if($diagnosaGizi->kunjungan->dokumen_terkunci, 423, 'Dokumen klinis sudah terkunci.');

        $diagnosaGizi->update([
            'divalidasi_oleh' => Auth::id(),
            'divalidasi_pada' => now(),
        ]);

        return back()->with('swal_success', 'Diagnosis gizi berhasil divalidasi SpGK.');
    }

    private function messages()
    {
        return [
            'required' => ':attribute wajib diisi.',
            'integer' => ':attribute harus berupa bilangan bulat.',
            'between' => ':attribute harus antara :min dan :max.',
            'in' => ':attribute tidak sesuai pilihan yang tersedia.',
            'exists' => ':attribute tidak ditemukan.',
        ];
    }
}
