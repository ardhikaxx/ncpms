<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreSkriningRequest extends FormRequest {
    public function authorize() { return true; }
    public function rules() {
        return [
            'metode_skrining' => 'required|in:MNA,NRS2002,MST,MUST,STAMP',
            'skor_penurunan_bb' => 'required|numeric|min:0|max:3',
            'skor_penurunan_asupan' => 'required|numeric|min:0|max:3',
            'skor_keparahan_penyakit' => 'required|numeric|min:0|max:3',
        ];
    }
    public function messages() {
        return [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'min' => ':attribute minimal :min.',
            'max' => ':attribute maksimal :max.',
            'in' => ':attribute tidak valid.',
        ];
    }
}
