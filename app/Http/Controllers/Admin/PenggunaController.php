<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function index()
    {
        $penggunas = Pengguna::withTrashed()->latest()->paginate(10);
        return view('admin.pengguna', compact('penggunas'));
    }

    public function create()
    {
        return redirect()->route('admin.pengguna.index');
    }

    public function show(Pengguna $pengguna)
    {
        return redirect()->route('admin.pengguna.index');
    }

    public function edit(Pengguna $pengguna)
    {
        return redirect()->route('admin.pengguna.index');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules(), $this->messages());
        $data['status_aktif'] = $request->boolean('status_aktif', true);
        Pengguna::create($data);

        return back()->with('swal_success', 'Akun pengguna berhasil dibuat.');
    }

    public function update(Request $request, Pengguna $pengguna)
    {
        $rules = $this->rules($pengguna->id);
        if (!$request->filled('password')) {
            unset($rules['password']);
        }

        $data = $request->validate($rules, $this->messages());
        if (!$request->filled('password')) {
            unset($data['password']);
        }
        $data['status_aktif'] = $request->boolean('status_aktif');

        $pengguna->update($data);
        return back()->with('swal_success', 'Akun pengguna berhasil diperbarui.');
    }

    public function destroy(Pengguna $pengguna)
    {
        $pengguna->update(['status_aktif' => false]);
        $pengguna->delete();

        return back()->with('swal_success', 'Akun pengguna berhasil dinonaktifkan.');
    }

    private function rules($id = null)
    {
        return [
            'nama_lengkap' => ['required', 'max:150'],
            'email' => ['required', 'email', 'unique:penggunas,email'.($id ? ','.$id : '')],
            'password' => [$id ? 'nullable' : 'required', 'min:8'],
            'peran' => ['required', 'in:spgk,dietisien,nutrisionis,perawat,admin_ti'],
            'nomor_sip' => ['nullable', 'max:50'],
            'nomor_str' => ['nullable', 'max:50'],
            'unit_kerja' => ['nullable', 'max:100'],
        ];
    }

    private function messages()
    {
        return [
            'required' => ':attribute wajib diisi.',
            'email' => ':attribute harus berupa alamat email yang valid.',
            'unique' => ':attribute sudah terdaftar dalam sistem.',
            'min' => ':attribute minimal :min karakter.',
            'max' => ':attribute maksimal :max karakter.',
            'in' => ':attribute tidak sesuai pilihan yang tersedia.',
        ];
    }
}
