<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BrandRequest extends FormRequest
{
    /**
     * Izinkan semua pengguna.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $id = $this->route('brand')?->id;

        return [
            'name' => ['required', 'string', 'max:100', 'unique:brands,name,'.$id],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama brand wajib diisi.',
            'name.string' => 'Nama brand harus berupa teks.',
            'name.max' => 'Nama brand maksimal :max karakter.',
            'name.unique' => 'Brand tersebut sudah terdaftar. Gunakan nama lain.',
        ];
    }
}