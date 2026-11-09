<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DivisionRequest extends FormRequest
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
        $id = $this->route('division')?->id;

        return [
            'name' => ['required', 'string', 'max:100', 'unique:divisions,name,'.$id],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama divisi wajib diisi.',
            'name.string' => 'Nama divisi harus berupa teks.',
            'name.max' => 'Nama divisi maksimal :max karakter.',
            'name.unique' => 'Divisi tersebut sudah terdaftar. Gunakan nama lain.',
        ];
    }
}