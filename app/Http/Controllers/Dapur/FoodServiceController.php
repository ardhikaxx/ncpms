<?php

namespace App\Http\Controllers\Dapur;

use App\Http\Controllers\Controller;
use App\Models\PreskripsiDiet;
use App\Models\Kunjungan;
use Illuminate\Http\Request;

class FoodServiceController extends Controller
{
    public function index()
    {
        // Ambil preskripsi diet terbaru untuk setiap kunjungan yang belum selesai atau selesai hari ini
        $kunjungans = Kunjungan::with(['pasien', 'preskripsiDiets' => function($q) {
            $q->latest()->limit(1);
        }])->whereDate('created_at', '>=', now()->subDays(30)) // misal rawat inap bisa 30 hari
          ->get()
          ->filter(function($k) {
              return $k->preskripsiDiets->isNotEmpty();
          });

        return view('dapur.index', compact('kunjungans'));
    }

    public function cetakEtiket($id)
    {
        $preskripsi = PreskripsiDiet::with('kunjungan.pasien')->findOrFail($id);
        
        \App\Traits\AuditsActivity::logCustomAction('print', 'App\Models\PreskripsiDiet', $preskripsi->id, 'Mencetak Etiket Makanan (Label Diet) untuk Dapur');

        return view('dapur.etiket', compact('preskripsi'));
    }
}
