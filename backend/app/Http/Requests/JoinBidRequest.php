<?php

namespace App\Http\Requests;

use App\Rules\AuctionBidLimit;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Override;

class JoinBidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id_lelang = $this->route('id');
        return [
            'id_barang' => ['required', 'exists:tb_barang,id_barang'],
            'penawaran_harga' => ['required', 'integer', 'min:0', new AuctionBidLimit($id_lelang)]
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'id_barang.required' => 'Barang harus dipilih.',
            'id_barang.exists' => 'Barang yang dipilih tidak terdaftar di sistem.',


            'penawaran_harga.required' => 'Nominal penawaran harga wajib diisi.',
            'penawaran_harga.integer' => 'Nominal penawaran harga harus berupa angka bulat.',
            'penawaran_harga.min' => 'Nominal penawaran harga tidak boleh bernilai negatif.',
        ];
    }

    #[Override]
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['messages' => 'Failed join bid', 'errors' => $validator->errors()], 422));
    }
}
