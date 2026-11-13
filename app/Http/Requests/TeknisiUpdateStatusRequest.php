<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeknisiUpdateStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'teknisi';
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:Diproses,Menunggu Komponen/Sparepart,Selesai,Rusak Total'],
            'catatan' => ['required', 'string', 'min:5', 'max:2000'],
            'komponen_dibutuhkan' => ['required_if:status,Menunggu Komponen/Sparepart', 'nullable', 'string', 'max:500'],
            'foto_bukti' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'Status perubahan wajib dipilih.',
            'status.in' => 'Status perubahan tidak valid.',
            'catatan.required' => 'Catatan progres penanganan wajib diisi.',
            'catatan.min' => 'Catatan progres minimal 5 karakter.',
            'catatan.max' => 'Catatan progres maksimal 2.000 karakter.',
            'komponen_dibutuhkan.required_if' => 'Nama komponen / sparepart yang dibutuhkan wajib diisi saat status Menunggu Komponen/Sparepart.',
            'foto_bukti.image' => 'Berkas bukti harus berupa gambar.',
            'foto_bukti.mimes' => 'Format foto bukti harus JPG atau PNG.',
            'foto_bukti.max' => 'Ukuran foto bukti maksimal 2 MB.',
        ];
    }
}

