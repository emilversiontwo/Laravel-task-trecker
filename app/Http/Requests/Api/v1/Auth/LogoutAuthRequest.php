<?php

namespace App\Http\Requests\Api\v1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LogoutAuthRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['sometimes', 'integer'],
            'token' => ['sometimes', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
