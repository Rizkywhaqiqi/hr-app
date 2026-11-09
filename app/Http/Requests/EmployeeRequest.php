<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
{
    /**
     * Izinkan semua pengguna (tidak ada autentikasi di aplikasi ini).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi untuk menambah & mengubah karyawan.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // Saat mengubah data, ID lama milik record ini harus dikecualikan
        // dari pemeriksaan keunikan supaya ID-nya sendiri tidak dianggap duplikat.
        $employee = $this->route('employee');

        return [
            'employee_id' => [
                'required',
                'regex:/^[A-Z]{2,5}-[0-9]{3,6}$/',
                'unique:employees,employee_id,'.($employee?->id),
            ],
            'full_name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[\p{L}\s.\'-]+$/u',
            ],
            'brand_id' => ['required', 'integer', 'exists:brands,id'],
            'position_id' => ['required', 'integer', 'exists:positions,id'],
            'division_id' => ['required', 'integer', 'exists:divisions,id'],
            'location_id' => ['required', 'integer', 'exists:locations,id'],
            'joined_date' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            'is_active' => ['required', 'boolean'],
        ];
    }

    /**
     * Pesan kesalahan validasi dalam Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'employee_id.required' => 'ID karyawan wajib diisi.',
            'employee_id.regex' => 'ID karyawan harus berformat 2-5 huruf besar, tanda hubung, lalu 3-6 angka (contoh: KS-001).',
            'employee_id.unique' => 'ID karyawan sudah digunakan. Gunakan ID yang berbeda.',

            'full_name.required' => 'Nama lengkap wajib diisi.',
            'full_name.min' => 'Nama lengkap minimal :min karakter.',
            'full_name.max' => 'Nama lengkap maksimal :max karakter.',
            'full_name.regex' => 'Nama lengkap hanya boleh berisi huruf (termasuk huruf beraksen), spasi, titik, apostrof, dan tanda hubung.',

            'brand_id.required' => 'Brand wajib dipilih.',
            'brand_id.exists' => 'Brand yang dipilih tidak tersedia.',

            'position_id.required' => 'Jabatan wajib dipilih.',
            'position_id.exists' => 'Jabatan yang dipilih tidak tersedia.',

            'division_id.required' => 'Divisi wajib dipilih.',
            'division_id.exists' => 'Divisi yang dipilih tidak tersedia.',

            'location_id.required' => 'Lokasi kerja wajib dipilih.',
            'location_id.exists' => 'Lokasi kerja yang dipilih tidak tersedia.',

            'joined_date.required' => 'Tanggal bergabung wajib diisi.',
            'joined_date.date_format' => 'Format tanggal bergabung harus YYYY-MM-DD (contoh: 2024-01-15).',
            'joined_date.before_or_equal' => 'Tanggal bergabung tidak boleh di masa depan.',

            'is_active.required' => 'Status karyawan wajib diisi.',
            'is_active.boolean' => 'Status karyawan tidak valid.',
        ];
    }

    /**
     * Siapkan data sebelum validasi: ubah ID ke huruf besar dan
     * ubah status checkbox menjadi boolean.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('employee_id')) {
            $this->merge([
                'employee_id' => strtoupper(trim((string) $this->employee_id)),
            ]);
        }

        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}