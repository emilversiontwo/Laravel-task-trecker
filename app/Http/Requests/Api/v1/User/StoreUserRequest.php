<?php
declare(strict_types=1);

namespace App\Http\Requests\Api\v1\User;

use App\Enums\Role\RoleEnum;
use App\Services\User\Dto\StoreUserDto;
use App\Services\User\Dto\UpdateUserDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', 'max:254', 'unique:users,email'],
            'password' => ['required'],
            'role' => ['required', Rule::enum(RoleEnum::class)],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function toDto(): StoreUserDto
    {
        $data = $this->validated();

        $roleEnum = RoleEnum::from($data['role']);

        return new StoreUserDto([
            ...$data,
            'role' => $roleEnum,
        ]);
    }
}
