<?php
declare(strict_types=1);

namespace App\Enums\Permission;

enum PermissionEnum: string
{
    case USERS_CRUD = 'users.crud';

    case TASKS_CRUD = 'tasks.crud';

    case USER_MANAGE = 'user.manage';

    case TASKS_MANAGE = 'tasks.manage';

    public function getValue(): string
    {
        return $this->value;
    }
}
