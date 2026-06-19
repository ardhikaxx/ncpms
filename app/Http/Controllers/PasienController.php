<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DiagnosisMedisUtama;
use App\Models\Kunjungan;
use App\Models\Pasien;
use App\Models\Pengguna;
use App\Models\RiwayatAlergiPasien;

class PasienController extends Controller
{
    public function index(Request $request) {
        abort_if(Auth::user()->peran === 'admin_ti', 403, 'Admin TI tidak boleh mengakses data klinis pasien.');

        $query = Pasien::with(['kunjungans' => fn ($q) => $q->limit(1)]);

        if ($request->filled('q')) {
            $kata = strtolower($request->q);
            $query->get()->filter(fn ($pasien) => str_contains(strtolower($pasien->nama_lengkap), $kata));
        }

        $pasiens = $query->latest()->paginate(10)->withQueryString();
        return view('pasien.index', compact('pasiens'));
    }

    public function create()
    {
        abort_unless(in_array(Auth::user()->peran, ['perawat', 'spgk']), 403, 'Hanya perawat atau SpGK yang dapat mendaftarkan pasien.');

        return view('pasien.create');
    }

    public function store(Request $request)
    {
        abort_unless(in_array(Auth::user()->peran, ['perawat', 'spgk']), 403, 'Hanya perawat atau SpGK yang dapat mendaftarkan pasien.');

        $data = $request->validate($this->rules(), $this->messages());
        $pasien = Pasien::create($data);

        if ($request->filled('nama_alergen')) {
            RiwayatAlergiPasien::create([
                'pasien_id' => $pasien->id,
                'jenis_alergi' => $request->jenis_alergi,
                'nama_alergen' => $request->nama_alergen,
                'reaksi' => $request->reaksi,
                'tingkat_keparahan' => $request->tingkat_keparahan,
                'dicatat_oleh' => Auth::id(),
            ]);
        }

        return redirect()->route('pasien.show', $pasien)->with('swal_success', 'Data pasien berhasil disimpan.');
    }

    public function show(Pasien $pasien)
    {
        abort_if(Auth::user()->peran === 'admin_ti', 403, 'Admin TI tidak boleh mengakses detail klinis pasien.');

        $pasien->load([
            'riwayatAlergi',
            'kunjungans.skriningGizi',
            'kunjungans.diagnosisMedisUtama',
            'kunjungans.antropometri',
            'kunjungans.biokimia',
        ]);

        $diagnosisMedis = DiagnosisMedisUtama::orderBy('kode_icd10')->get();
        $dietisiens = Pengguna::whereIn('peran', ['dietisien', 'spgk'])->where('status_aktif', true)->get();
        return view('pasien.show', compact('pasien', 'diagnosisMedis', 'dietisiens'));
    }

    public function edit(Pasien $pasien)
    {
        abort_unless(in_array(Auth::user()->peran, ['perawat', 'spgk']), 403, 'Hanya perawat atau SpGK yang dapat mengubah identitas pasien.');

        return view('pasien.edit', compact('pasien'));
    }

    public function update(Request $request, Pasien $pasien)
    {
        abort_unless(in_array(Auth::user()->peran, ['perawat', 'spgk']), 403, 'Hanya perawat atau SpGK yang dapat mengubah identitas pasien.');

        $data = $request->validate($this->rules($pasien->id), $this->messages());
        $pasien->update($data);
        return redirect()->route('pasien.show', $pasien)->with('swal_success', 'Data pasien berhasil diperbarui.');
    }

    public function destroy(Pasien $pasien)
    {
        abort_unless(Auth::user()->peran === 'spgk', 403, 'Hanya SpGK yang dapat menonaktifkan data pasien.');

        $pasien->delete();
        return redirect()->route('pasien.index')->with('swal_success', 'Data pasien berhasil dinonaktifkan.');
    }

    public function storeKunjungan(Request $request, Pasien $pasien)
    {
        abort_unless(in_array(Auth::user()->peran, ['perawat', 'spgk']), 403, 'Hanya perawat atau SpGK yang dapat mendaftarkan kunjungan.');

        $data = $request->validate([
            'tipe_kunjungan' => ['required', 'in:mandiri,rujukan_internal,rujukan_eksternal'],
            'asal_rujukan' => ['nullable', 'max:200'],
            'tanggal_kunjungan' => ['required', 'date', 'before_or_equal:today'],
            'dietisien_id' => ['nullable', 'exists:penggunas,id'],
            'diagnosis_medis_utama_id' => ['nullable', 'exists:diagnosis_medis_utamas,id'],
        ], $this->messages());

        $data['pasien_id'] = $pasien->id;
        $data['nomor_kunjungan'] = $this->nomorKunjungan();
        $data['status'] = 'terdaftar';
        $data['waktu_registrasi'] = now();
        $data['perawat_id'] = Auth::id();

        $kunjungan = Kunjungan::create($data);
        return redirect()->route('kunjungan.show', $kunjungan)->with('swal_success', 'Kunjungan berhasil didaftarkan.');
    }

    private function nomorKunjungan()
    {
        $prefix = 'KGZ-'.now()->format('Ymd').'-';
        $urutan = Kunjungan::where('nomor_kunjungan', 'like', $prefix.'%')->count() + 1;
        return $prefix.str_pad($urutan, 4, '0', STR_PAD_LEFT);
    }

    private function rules()
    {
        return [
            'nomor_rekam_medis' => ['required', 'max:50'],
            'nik' => ['nullable', 'digits:16'],
            'nama_lengkap' => ['required', 'max:150'],
            'tempat_lahir' => ['nullable', 'max:100'],
            'tanggal_lahir' => ['required', 'date', 'before:today'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'golongan_darah' => ['nullable', 'in:A,B,AB,O,tidak_diketahui'],
            'nomor_telepon' => ['nullable', 'max:30'],
            'alamat' => ['nullable'],
            'nomor_bpjs' => ['nullable', 'max:30'],
            'status_aktif' => ['nullable', 'boolean'],
        ];
    }

    private function messages()
    {
        return [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'digits' => ':attribute harus terdiri dari :digits digit.',
            'max' => ':attribute maksimal :max karakter.',
            'in' => ':attribute tidak sesuai pilihan yang tersedia.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'before' => ':attribute harus sebelum hari ini.',
            'before_or_equal' => ':attribute tidak boleh lebih dari hari ini.',
            'exists' => ':attribute tidak ditemukan.',
        ];
    }
}
