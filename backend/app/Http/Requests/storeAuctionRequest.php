<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Override;

class storeAuctionRequest extends FormRequest
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
        return [
            'nama_barang' => ['required', 'unique:tb_barang,nama_barang', 'max:25'],
            'tgl' => ['required', 'date'],
            'harga_awal' => ['required', 'integer', 'min:0'],
            'harga_akhir' => ['required', 'integer', 'min:0'],
            'tgl_mulai_lelang' => ['required' , 'date'],
            'tgl_akhir_lelang' => ['required' , 'date' , 'after:tgl_mulai_lelang'],
            'id_user' => ['required', 'exists:tb_masyarakat,id_user'],
            'deskripsi_barang' => ['required', 'max:100'],
            'thumbnail' => ['required', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'id_kategori_barang' => ['required', 'exists:tb_kategori_barang,id_kategori_barang'],
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'nama_barang.required' => 'Nama asset lelang wajib diisi',
            'nama_barang.unique' => 'Nama asset lelang sudah digunakan',
            'nama_barang.max' => 'Panjang nama maksimal 25 karakter',

            'tgl.required' => 'Tanggal wajib diisi',
            'tgl.date' => 'Tanggal invalid',

            'tgl_mulai_lelang.required' => 'Tanggal mulai wajib diisi',
            'tgl_mulai_lelang.date' => 'Tanggal invalid',

            'tgl_akhir_lelang.required' => 'Tanggal akhir wajib diisi',
            'tgl_akhir_lelang.date' => 'Tanggal invalid',

            'harga_awal.required' => 'Harga awal wajib diisi',
            'harga_awal.integer' => 'Harga awal invalid',
            'harga_awal.min' => 'Batas awal harga adalah 0',

            'harga_akhir.required' => 'Harga akhir wajib diisi',
            'harga_akhir.integer' => 'Harga akhir invalid',
            'harga_akhir.min' => 'Batas awal harga adalah 0',

            'deskripsi_barang.required' => 'Deskripsi barang wajib diisi',
            'deskripsi_barang.max' => 'Panjang deskripsi barang maksimal 100 karakter',

            'thumbnail.required' => 'Foto barang wajib diisi',
            'thumbnail.image' => 'Foto invalid',
            'thumbnail.mimes' => 'Ekstensi foto wajib jpg,jpeg,png,webp',
            'thumbnail.max' => 'Ukuran foto maksimal 2 MB',

            'id_kategori_barang.required' => 'Kategori wajib diisi',
            'id_kategori_barang.exists' => 'Kategori tidak ditemukan',

            'id_user.required' => 'Pengguna wajib diisi',
            'id_user.exists' => 'Pengguna tidak ditemukan',

        ];
    }


    #[Override]
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['message' => 'Invalid field', 'errors' => $validator->errors()], 422));
    }
}
