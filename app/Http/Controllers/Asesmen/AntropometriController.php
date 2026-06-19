<?php
namespace App\Http\Controllers\Asesmen;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAntropometriRequest;
use App\Services\AntropometriService;
use App\Models\DataAntropometri;
use App\Models\Kunjungan;
use Illuminate\Support\Facades\Auth;

class AntropometriController extends Controller {
    protected $service;
    public function __construct(AntropometriService $service) { $this->service = $service; }

    public function index(Request $request) { 
        $kunjungan_id = $request->query('kunjungan_id');
        $kunjungan = Kunjungan::with('pasien')->findOrFail($kunjungan_id);
        $data = DataAntropometri::where('kunjungan_id', $kunjungan_id)->latest()->first();
        return view('asesmen.antropometri', compact('kunjungan', 'data')); 
    }

    public function store(StoreAntropometriRequest $request) {
        $bb = $request->berat_badan_kg;
        $tb = $request->tinggi_badan_cm;
        $imt = $this->service->hitungIMT($bb, $tb);
        $status = $this->service->kategoriIMT($imt);

        DataAntropometri::create([
            'kunjungan_id' => $request->kunjungan_id,
            'tanggal_pengukuran' => now()->toDateString(),
            'berat_badan_kg' => encrypt((string)$bb),
            'tinggi_badan_cm' => encrypt((string)$tb),
            'imt' => encrypt((string)$imt),
            'status_gizi_imt' => $status,
            'dicatat_oleh' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Data Antropometri berhasil disimpan.');
    }
}
