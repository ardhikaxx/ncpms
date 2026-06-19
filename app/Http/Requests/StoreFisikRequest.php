<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreFisikRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() {
        return [
            'tekanan_darah_sistolik' => ['nullable', 'integer', 'min:50', 'max:250'],
            'tekanan_darah_diastolik' => ['nullable', 'integer', 'min:30', 'max:150'],
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
