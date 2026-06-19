<?php

namespace App\Http\Controllers\Monitoring;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\Monitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    public function index()
    {
        $kunjungans = Kunjungan::with(['pasien', 'antropometri', 'biokimia', 'preskripsiDiets'])
            ->whereIn('status', ['dalam_pelayanan', 'selesai'])
            ->latest('tanggal_kunjungan')
            ->get();
        $monitorings = Monitoring::with(['kunjungan.pasien', 'pelaksana'])->latest()->paginate(10);

        return view('monitoring.index', compact('kunjungans', 'monitorings'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kunjungan_id' => ['required', 'exists:kunjungans,id'],
            'parameter_dipantau' => ['required'],
            'evaluasi_anthropometri' => ['nullable'],
            'evaluasi_biokimia' => ['nullable'],
            'evaluasi_asupan' => ['nullable'],
            'evaluasi_kepatuhan_diet' => ['nullable', 'in:patuh,cukup_patuh,tidak_patuh'],
            'persen_sisa_makanan' => ['nullable', 'numeric', 'between:0,100'],
            'kesimpulan' => ['nullable'],
            'rekomendasi_lanjutan' => ['nullable'],
            'perlu_rujukan' => ['nullable', 'boolean'],
            'tujuan_rujukan' => ['nullable'],
            'rencana_kunjungan_berikutnya' => ['nullable', 'date', 'after:today'],
        ], $this->messages());

        $kunjungan = Kunjungan::findOrFail($data['kunjungan_id']);
        abort_if($kunjungan->dokumen_terkunci, 423, 'Dokumen klinis sudah terkunci.');

        $data['parameter_dipantau'] = array_values(array_filter(explode(',', $data['parameter_dipantau'])));
        $data['perlu_rujukan'] = $request->boolean('perlu_rujukan');
        $data['dilakukan_oleh'] = Auth::id();
        $data['kunjungan_sebelumnya_id'] = Kunjungan::where('pasien_id', $kunjungan->pasien_id)
            ->where('id', '!=', $kunjungan->id)
            ->latest('tanggal_kunjungan')
            ->value('id');

        Monitoring::updateOrCreate(['kunjungan_id' => $kunjungan->id], $data);
        return back()->with('swal_success', 'Monitoring dan evaluasi berhasil disimpan.');
    }

    private function messages()
    {
        return [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'between' => ':attribute harus antara :min dan :max.',
            'in' => ':attribute tidak sesuai pilihan yang tersedia.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'after' => ':attribute harus setelah hari ini.',
            'exists' => ':attribute tidak ditemukan.',
        ];
    }
}
