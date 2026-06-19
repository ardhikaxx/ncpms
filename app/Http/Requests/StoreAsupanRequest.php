<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreAsupanRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() {
        return [
            'metode' => ['required', 'in:food_recall_24h,food_recall_48h,food_recall_72h,ffq_semi_kuantitatif'],
            'tanggal_recall' => ['required', 'date', 'before_or_equal:today'],
            'detail_asupan' => ['required', 'string'],
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
