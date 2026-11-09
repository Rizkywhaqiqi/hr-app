<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest
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
        $id = $this->route('location')?->id;

        return [
            'name' => ['required', 'string', 'max:100', 'unique:locations,name,'.$id],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lokasi wajib diisi.',
            'name.string' => 'Nama lokasi harus berupa teks.',
            'name.max' => 'Nama lokasi maksimal :max karakter.',
            'name.unique' => 'Lokasi tersebut sudah terdaftar. Gunakan nama lain.',
        ];
    }
}