<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MemberReopenTiketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'member';
    }

    public function rules(): array
    {
        return [
            'alasan' => ['required', 'string', 'min:5', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'alasan.required' => 'Alasan membuka kembali tiket wajib diisi.',
            'alasan.min' => 'Alasan membuka kembali tiket minimal 5 karakter.',
            'alasan.max' => 'Alasan membuka kembali tiket maksimal 1.000 karakter.',
        ];
    }
}
