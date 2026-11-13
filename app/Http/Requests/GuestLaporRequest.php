<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuestLaporRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Terbuka untuk umum (publik) tanpa wajib login (PRD v1.1 Alur 1)
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_pelapor' => ['required', 'string', 'max:100'],
            'kontak_pelapor' => ['nullable', 'string', 'max:50'],
            'judul' => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string'],
            'fotos' => ['nullable', 'array', 'max:3'],
            'fotos.*' => ['image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_pelapor.required' => 'Nama pelapor wajib diisi.',
            'judul.required' => 'Judul atau ringkasan keluhan kerusakan wajib diisi.',
            'deskripsi.required' => 'Deskripsi kronologi/gejala kerusakan wajib diisi.',
            'fotos.max' => 'Maksimal 3 foto bukti kerusakan yang dapat dilampirkan.',
            'fotos.*.image' => 'File lampiran harus berupa gambar.',
            'fotos.*.mimes' => 'Format foto harus berupa JPG atau PNG.',
            'fotos.*.max' => 'Ukuran masing-masing foto maksimal 2 MB.',
        ];
    }
}

