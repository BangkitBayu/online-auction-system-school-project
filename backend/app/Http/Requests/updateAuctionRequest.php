<?php

namespace App\Http\Requests;

use App\Models\Barang;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Override;

class updateAuctionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    #[Override]
    protected function prepareForValidation()
    {
        $this->merge([
            'harga_awal' => (int) $this->harga_awal,
            'harga_akhir' => (int) $this->harga_akhir,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $auctionId = $this->route('id');
        // Mengabaikan id data yang sedang diedit
        return [
            'nama_barang' => ['sometimes', Rule::unique('tb_barang', 'id_barang')->ignore($auctionId, 'id_barang'), 'max:25'],
            'tgl' => ['sometimes', 'date'],
            'harga_awal' => ['sometimes', 'integer', 'min:0'],
            'harga_akhir' => ['sometimes', 'integer', 'min:0'],
            'tgl_mulai_lelang' => ['sometimes' , 'date'],
            'tgl_akhir_lelang' => ['sometimes' , 'date' , 'after:tgl_mulai_lelang'],
            'id_user' => ['sometimes', 'exists:tb_masyarakat,id_user'],
            'deskripsi_barang' => ['sometimes', 'max:100'],
            'thumbnail' => ['sometimes', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'id_kategori_barang' => ['sometimes', 'exists:tb_kategori_barang,id_kategori_barang'],
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'nama_barang.unique' => 'Nama asset lelang sudah digunakan',
            'nama_barang.max' => 'Panjang nama maksimal 25 karakter',

            'tgl.date' => 'Tanggal invalid',

            'harga_awal.integer' => 'Harga awal invalid',
            'harga_awal.min' => 'Batas awal harga adalah 0',

            'deskripsi_barang.required' => 'Deskripsi barang wajib diisi',
            'deskripsi_barang.max' => 'Panjang deskripsi barang maksimal 100 karakter',

            'thumbnail.image' => 'Foto invalid',
            'thumbnail.mimes' => 'Ekstensi foto wajib jpg,jpeg,png,webp',
            'thumbnail.max' => 'Ukuran foto maksimal 2 MB',

            'id_kategori_barang.exists' => 'Kategori tidak ditemukan'

        ];
    }

    #[Override]
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['message' => 'Invalid field', 'errors' => $validator->errors()], 422));
    }
}
