<?php
declare(strict_types=1);

namespace App\Http\Requests\Api\v1\Auth;

use App\Services\Auth\Dto\RegistrationAuthDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class RegistrationAuthRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function toDto(): RegistrationAuthDto
    {
        $data = $this->validated();

        return new RegistrationAuthDto([
            ...$data,
            'token_name' => $this->device_name ?? $this->userAgent() ?? Str::random(20),
        ]);
    }
}
