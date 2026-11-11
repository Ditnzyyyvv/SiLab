<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberTiketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'member';
    }

    public function rules(): array
    {
        return [
            'aset_id' => ['required', 'exists:asets,id'],
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
            'aset_id.required' => 'Perangkat aset laboratorium wajib dipilih.',
            'aset_id.exists' => 'Perangkat aset yang dipilih tidak valid.',
            'judul.required' => 'Judul keluhan kerusakan wajib diisi.',
            'deskripsi.required' => 'Deskripsi detail kerusakan wajib diisi.',
            'fotos.max' => 'Maksimal 3 foto bukti yang dapat dilampirkan.',
            'fotos.*.image' => 'File lampiran harus berupa gambar.',
            'fotos.*.mimes' => 'Format foto harus berupa JPG atau PNG.',
            'fotos.*.max' => 'Ukuran masing-masing foto maksimal 2 MB.',
        ];
    }
}
