<?php

namespace App\Http\Controllers\Client;

use App\Models\Client;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // Assuming only authenticated admins can update clients.
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
{
    $client = $this->route('client');

    return [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:clients,email,' . $client->id,
        'password' => 'nullable|string|min:8|confirmed',
        'phone' => ['nullable', 'regex:/^\+?[0-9]{7,15}$/'],
        'address' => 'nullable|string|max:255',
    ];
}

}
