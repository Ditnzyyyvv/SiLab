<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AsetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'admin';
    }

    public function rules(): array
    {
        $asetId = $this->route('aset') ? $this->route('aset')->id : null;

        return [
            'kode_aset' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('asets', 'kode_aset')->ignore($asetId),
            ],
            'nama' => ['required', 'string', 'max:150'],
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'lab_id' => ['required', 'exists:labs,id'],
            'merek' => ['nullable', 'string', 'max:100'],
            'spesifikasi' => ['nullable', 'string'],
            'tanggal_perolehan' => ['nullable', 'date'],
            'kondisi' => [
                'required',
                Rule::in(['Baik', 'Rusak Ringan', 'Rusak Berat', 'Dalam Perbaikan', 'Tidak Layak Pakai']),
            ],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama aset wajib diisi.',
            'kategori_id.required' => 'Kategori aset wajib dipilih.',
            'kategori_id.exists' => 'Kategori tidak valid.',
            'lab_id.required' => 'Laboratorium wajib dipilih.',
            'lab_id.exists' => 'Laboratorium tidak valid.',
            'kondisi.required' => 'Kondisi aset wajib ditentukan.',
            'kondisi.in' => 'Pilihan kondisi aset tidak valid.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format foto harus berupa JPG atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',
        ];
    }
}

