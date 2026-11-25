<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteGoogleProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'nama' => ['required', 'string', 'min:3', 'max:150'],
            'kelas_jabatan' => ['required', 'string', 'min:2', 'max:100'],
            'no_hp' => ['nullable', 'string', 'max:20'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama lengkap asli wajib diisi agar mudah dikenali.',
            'nama.min' => 'Nama lengkap minimal 3 karakter.',
            'kelas_jabatan.required' => 'Kelas atau jabatan (misal: XI RPL 2 / Guru) wajib diisi.',
            'kelas_jabatan.min' => 'Kelas atau jabatan minimal 2 karakter.',
        ];
    }
}
