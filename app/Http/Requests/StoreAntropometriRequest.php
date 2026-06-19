<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class StoreAntropometriRequest extends FormRequest {
    public function authorize() { return true; }
    public function rules() {
        return [
            'berat_badan_kg' => 'required|numeric|min:1',
            'tinggi_badan_cm' => 'required|numeric|min:10',
        ];
    }
    public function messages() {
        return [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'min' => ':attribute minimal :min.',
        ];
    }
}
