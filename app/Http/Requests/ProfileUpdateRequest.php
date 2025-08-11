<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            
            'cpf_cnpj' => ['nullable', 'string', 'max:18'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg', 'max:1024'],
            'logo' => ['nullable', 'image', 'mimes:jpg,png,svg', 'max:1024'],
            'signature' => ['nullable', 'image', 'mimes:jpg,png,svg', 'max:1024'],

            // NOVAS REGRAS ADICIONADAS
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'behance_url' => ['nullable', 'url', 'max:255'],
            ];
    }
}
