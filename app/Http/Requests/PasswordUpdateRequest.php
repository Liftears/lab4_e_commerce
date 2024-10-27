<?php

// App\Http\Requests\PasswordUpdateRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PasswordUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'current_password' => ['required', 'current_password'],
            'password' => 'required|string|min:8|confirmed',

        ];
    }

    public function authorize()
    {
        return true; // Allow all authenticated users to update password
    }
}

