<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LabRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        $labId = $this->route('lab') ? $this->route('lab')->id : null;

        return [
            'kode' => [
                'required',
                'string',
                'max:20',
                Rule::unique('labs', 'kode')->ignore($labId),
            ],
            'nama' => ['required', 'string', 'max:100'],
            'lokasi' => ['required', 'string', 'max:255'],
            'penanggung_jawab_id' => ['required', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode.required' => 'Kode lab wajib diisi.',
            'kode.unique' => 'Kode lab sudah digunakan.',
            'nama.required' => 'Nama laboratorium wajib diisi.',
            'lokasi.required' => 'Lokasi lab wajib diisi.',
            'penanggung_jawab_id.required' => 'Penanggung jawab wajib dipilih.',
            'penanggung_jawab_id.exists' => 'Pengguna penanggung jawab tidak ditemukan.',
        ];
    }
}

