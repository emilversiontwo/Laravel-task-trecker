<?php
declare(strict_types=1);

namespace App\Http\Requests\Api\v1\User;

use App\Enums\Permission\PermissionEnum;
use App\Enums\Role\RoleEnum;
use App\Exceptions\AppLogicException;
use App\Services\User\Dto\UpdateUserDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response as ResponseCode;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['sometimes'],
            'email' => ['sometimes', 'email', 'max:254', 'unique:users,email'],
            'password' => ['sometimes'],
            'role' => ['nullable', Rule::enum(RoleEnum::class)],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @throws AppLogicException
     */
    public function toDto(int $user_id): UpdateUserDto
    {
        $data = $this->validated();

        $roleEnum = array_key_exists('role', $data) ? RoleEnum::from($data['role']) : null;

        if ($roleEnum !== null && !$this->user()->hasPermissionTo(PermissionEnum::USERS_CRUD)) {
            $roleEnum = null;
        }

        return new UpdateUserDto([
            ...$data,
            'user_id' => $user_id,
            'role' => $roleEnum,
        ]);
    }
}
