<?php

namespace App\Http\Requests;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        $client = $this->route('client');

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . ($client->id ?? 'NULL'),
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => ['nullable', 'regex:/^\+?[0-9]{7,15}$/'],
            'address' => 'nullable|string|max:255',
        ];
    }
}
