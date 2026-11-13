<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TolakTiketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'alasan_penolakan' => ['required', 'string', 'min:5', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'alasan_penolakan.required' => 'Alasan penolakan tiket wajib diisi.',
            'alasan_penolakan.min' => 'Alasan penolakan minimal 5 karakter.',
            'alasan_penolakan.max' => 'Alasan penolakan maksimal 1.000 karakter.',
        ];
    }
}

