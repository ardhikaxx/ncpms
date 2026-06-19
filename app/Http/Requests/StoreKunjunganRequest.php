<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreKunjunganRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() {
        return [
            'tipe_kunjungan' => ['required', 'in:mandiri,rujukan_internal,rujukan_eksternal'],
            'asal_rujukan' => ['nullable', 'max:200'],
            'tanggal_kunjungan' => ['required', 'date', 'before_or_equal:today'],
            'dietisien_id' => ['nullable', 'exists:penggunas,id'],
            'diagnosis_medis_utama_id' => ['nullable', 'exists:diagnosis_medis_utamas,id'],
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
