<?php
declare(strict_types=1);

namespace App\Services\User\Dto;

use App\Enums\Role\RoleEnum;
use App\Helpers\Dto\Dto;

class StoreUserDto extends UserIdDto
{
    public string $name;

    public string $email;

    public string $password;

    public RoleEnum $role;
}
