<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PositionRequest extends FormRequest
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
        $id = $this->route('position')?->id;

        return [
            'name' => ['required', 'string', 'max:100', 'unique:positions,name,'.$id],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama jabatan wajib diisi.',
            'name.string' => 'Nama jabatan harus berupa teks.',
            'name.max' => 'Nama jabatan maksimal :max karakter.',
            'name.unique' => 'Jabatan tersebut sudah terdaftar. Gunakan nama lain.',
        ];
    }
}