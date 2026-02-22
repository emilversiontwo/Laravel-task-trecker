<?php
declare(strict_types=1);

namespace App\Services\User\Dto;

use App\Enums\Role\RoleEnum;
use App\Helpers\Dto\Dto;

class UpdateUserDto extends UserIdDto
{
    public ?string $name = null;

    public ?string $email = null;

    public ?string $password = null;

    public ?RoleEnum $role = null;
}
