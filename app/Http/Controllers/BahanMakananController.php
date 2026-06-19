<?php

namespace App\Http\Controllers;

use App\Models\BahanMakanan;
use Illuminate\Http\Request;

class BahanMakananController extends Controller
{
    public function index(Request $request)
    {
        $query = BahanMakanan::query();
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_bahan', 'like', "%{$search}%")
                  ->orWhere('kategori', 'like', "%{$search}%");
        }
        $bahanMakanans = $query->orderBy('nama_bahan')->paginate(15);
        return view('bahan-makanan.index', compact('bahanMakanans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_bahan' => 'required|string|max:200',
            'kategori' => 'required|in:karbohidrat,protein_hewani,protein_nabati,sayuran,buah,lemak,minuman,bumbu,lainnya',
            'porsi_standar_gram' => 'required|numeric',
            'energi_kkal' => 'required|numeric',
            'protein_gram' => 'required|numeric',
            'lemak_gram' => 'required|numeric',
            'karbohidrat_gram' => 'required|numeric',
            'sumber_data' => 'nullable|string|max:100',
        ]);

        BahanMakanan::create($data);
        return redirect()->route('bahan-makanan.index')->with('success', 'Bahan makanan berhasil ditambahkan.');
    }

    public function update(Request $request, BahanMakanan $bahanMakanan)
    {
        $data = $request->validate([
            'nama_bahan' => 'required|string|max:200',
            'kategori' => 'required|in:karbohidrat,protein_hewani,protein_nabati,sayuran,buah,lemak,minuman,bumbu,lainnya',
            'porsi_standar_gram' => 'required|numeric',
            'energi_kkal' => 'required|numeric',
            'protein_gram' => 'required|numeric',
            'lemak_gram' => 'required|numeric',
            'karbohidrat_gram' => 'required|numeric',
            'sumber_data' => 'nullable|string|max:100',
        ]);

        $bahanMakanan->update($data);
        return redirect()->route('bahan-makanan.index')->with('success', 'Bahan makanan berhasil diperbarui.');
    }

    public function destroy(BahanMakanan $bahanMakanan)
    {
        $bahanMakanan->delete();
        return redirect()->route('bahan-makanan.index')->with('success', 'Bahan makanan berhasil dihapus.');
    }
}
