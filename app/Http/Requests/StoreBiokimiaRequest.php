<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreBiokimiaRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() {
        return [
            'sumber_data' => ['required', 'in:lab_internal,input_manual,satusehat'],
            'tanggal_pemeriksaan' => ['required', 'date', 'before_or_equal:today'],
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
