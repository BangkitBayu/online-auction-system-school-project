<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Override;

class StoreOfficerRequest extends FormRequest
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
        return [
            'nama_petugas' => ['required', 'regex:/^[A-Z][a-z]+(\s[A-Z][a-z]+)*$/', 'max:255'],
            'username' => ['required', Rule::unique('tb_masyarakat', 'username'), Rule::unique('tb_petugas', 'username'), 'max:25'],
            'telp' => ['required', 'regex:/^08[0-9]+$/', 'min:10', 'max:12'],
            'password' => ['required', 'min:8', 'max:12'],
            'confirm_password' => ['required', 'confirmed:password']
        ];
    }

    #[Override]
    public function messages(): array
    {
        return [
            'nama_petugas.required' => "Nama lengkap wajib diisi",
            'nama_petugas.regex' => 'Nama lengkap harus mengandung huruf, diawali kapital dan dipisah spasi',
            'nama_petugas.max' => 'Panjang nama lengkap maksimal 255 digit',

            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'username.max' => 'Panjang username maksimal 25 digit',

            'telp.required' => 'Nomor telepon wajib diisi',
            'telp.regex' => 'Nomor telepon wajib angka dan diawali 08',
            'telp.min' => 'Panjang nomor telepon minimal 10 digit',
            'telp.max' => 'Panjang nomor telepon maksimal 12 digit',

            'password.required' => 'Password wajib diisi',
            'password.min' => 'Panjang password minimal 8 digit',
            'password.max' => 'Panjang password maksimal 12 digit',

            'confirm_password.required' => 'Konfirmasi password wajib diisi',
            'confirm_password.confirmed' => 'Konfirmasi password tidak cocok dengan password',
        ];
    }

    #[Override]
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(['message' => 'Invalid field', 'errors' => $validator->errors()], 422));
    }
}
