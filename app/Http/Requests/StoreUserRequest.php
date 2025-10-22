<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool { return $this->user()->can('manage users'); }

    public function rules(): array
    {
        return [
            'nama' => ['required','string','max:255'],
            'username' => ['required','string','max:50','unique:users,username'],
            'email' => ['nullable','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8','confirmed'], // expect password_confirmation input
            'telepon' => ['nullable','string','max:20'],
            'gaji' => ['nullable','numeric'],
            'alamat' => ['nullable','string'],
            'cabang_id' => ['nullable','exists:cabangs,id'],
            'roles' => ['nullable','array'],
            'roles.*' => ['string','exists:roles,name'],
        ];
    }
}
