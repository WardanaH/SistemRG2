<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCabangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'direktur', 'manajemen', 'supervisor']);
    }


    public function rules(): array
    {
        return [
            'kode' => ['required', 'string', 'max:20', 'unique:cabangs,kode'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'jenis' => ['required', 'in:pusat,cabang'],
        ];
    }
}
