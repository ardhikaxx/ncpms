<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreAntropometriRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() {
        return [
            'berat_badan_kg' => ['required', 'numeric', 'min:1', 'max:300'],
            'tinggi_badan_cm' => ['required', 'numeric', 'min:20', 'max:250'],
            'tanggal_pengukuran' => ['required', 'date', 'before_or_equal:today'],
        ];
    }
    public function messages() {
        return [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'integer' => ':attribute harus berupa bilangan bulat.',
            'min' => ':attribute minimal :min.',
            'max' => ':attribute maksimal :max.',
            'in' => ':attribute tidak sesuai pilihan.',
            'date' => ':attribute harus berupa tanggal valid.',
            'before_or_equal' => ':attribute tidak boleh lebih dari hari ini.',
            'exists' => ':attribute tidak valid.',
        ];
    }
}
