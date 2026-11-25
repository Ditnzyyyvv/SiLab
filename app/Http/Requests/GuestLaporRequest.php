<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GuestLaporRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Terbuka untuk umum (publik) tanpa wajib login (PRD v1.1 Alur 1)
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nama_pelapor' => ['required', 'string', 'max:100'],
            'kontak_pelapor' => ['nullable', 'string', 'max:50'],
            'judul' => ['required', 'string', 'max:150'],
            'deskripsi' => ['required', 'string'],
            'fotos' => ['nullable', 'array', 'max:3'],
            'fotos.*' => ['image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];

        // Jika dikirim dari form beranda (tanpa parameter kode_aset di URL), aset_id wajib dipilih dan masih aktif
        if (! $this->route('kode_aset')) {
            $rules['aset_id'] = [
                'required',
                Rule::exists('asets', 'id')->whereNull('deleted_at'),
            ];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'aset_id.required' => 'Perangkat aset laboratorium wajib dipilih.',
            'aset_id.exists' => 'Perangkat aset yang dipilih tidak ditemukan atau sudah tidak aktif.',
            'nama_pelapor.required' => 'Nama lengkap & kelas/jabatan pelapor wajib diisi.',
            'judul.required' => 'Judul atau ringkasan keluhan kerusakan wajib diisi.',
            'deskripsi.required' => 'Deskripsi kronologi/gejala kerusakan wajib diisi.',
            'fotos.max' => 'Maksimal 3 foto bukti kerusakan yang dapat dilampirkan.',
            'fotos.*.image' => 'File lampiran harus berupa gambar.',
            'fotos.*.mimes' => 'Format foto harus berupa JPG atau PNG.',
            'fotos.*.max' => 'Ukuran masing-masing foto maksimal 2 MB.',
        ];
    }
}

