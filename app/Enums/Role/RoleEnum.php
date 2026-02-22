<?php
declare(strict_types=1);

namespace App\Enums\Role;

enum RoleEnum: string
{
    case ADMIN = 'admin';

    case USER = 'user';

    public function getValue(): string
    {
        return $this->value;
    }
}
