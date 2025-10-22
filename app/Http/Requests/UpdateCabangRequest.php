<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCabangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'kode_cabang' => 'required|string|max:50|unique:cabangs,kode_cabang,' . $this->cabang->id,
            'email' => 'nullable|email',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
            'jenis_cabang' => 'required|in:pusat,cabang',
        ];
    }
}
