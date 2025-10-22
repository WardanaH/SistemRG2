<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()->can('manage users'); }

    public function rules(): array
    {
        $userId = $this->route('user')->id ?? null;

        return [
            'nama' => ['required','string','max:255'],
            'username' => ['required','string','max:50', Rule::unique('users','username')->ignore($userId)],
            'email' => ['nullable','email','max:255', Rule::unique('users','email')->ignore($userId)],
            'password' => ['nullable','string','min:8','confirmed'],
            'telepon' => ['nullable','string','max:20'],
            'gaji' => ['nullable','numeric'],
            'alamat' => ['nullable','string'],
            'cabang_id' => ['nullable','exists:cabangs,id'],
            'roles' => ['nullable','array'],
            'roles.*' => ['string','exists:roles,name'],
        ];
    }
}
