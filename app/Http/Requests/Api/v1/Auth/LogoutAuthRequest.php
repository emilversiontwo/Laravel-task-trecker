<?php
declare(strict_types=1);

namespace App\Http\Requests\Api\v1\Auth;

use App\Services\Auth\Dto\LogoutAuthDto;
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

    public function toDto(): LogoutAuthDto
    {
        $data = $this->validated();

        return new LogoutAuthDto([
            ...$data,
            'user' => $this->user(),
        ]);
    }
}
