<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DisposisiTiketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'teknisi_id' => ['required', 'exists:users,id'],
            'prioritas' => ['required', 'in:Rendah,Sedang,Tinggi'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'teknisi_id.required' => 'Pilih salah satu teknisi yang ditugaskan.',
            'teknisi_id.exists' => 'Teknisi yang dipilih tidak valid.',
            'prioritas.required' => 'Tingkat prioritas wajib dipilih.',
            'prioritas.in' => 'Prioritas harus bernilai Rendah, Sedang, atau Tinggi.',
            'catatan.max' => 'Catatan disposisi maksimal 1.000 karakter.',
        ];
    }
}

