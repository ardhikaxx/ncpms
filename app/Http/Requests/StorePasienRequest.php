<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StorePasienRequest extends FormRequest {
    public function authorize() { return $this->user()->can('create', \App\Models\Pasien::class); }
    public function rules() {
        return [
            'nik' => 'required|digits:16|unique:pasiens,nik',
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
        ];
    }
}
