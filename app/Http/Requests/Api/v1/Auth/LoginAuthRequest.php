<?php
declare(strict_types=1);

namespace App\Http\Requests\Api\v1\Auth;

use App\Services\Auth\Dto\LoginAuthDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class LoginAuthRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function toDto(): LoginAuthDto
    {
        $data = $this->validated();

        return new LoginAuthDto([
            ...$data,
            'token_name' => $this->device_name ?? $this->userAgent() ?? Str::random(20),
        ]);
    }
}
