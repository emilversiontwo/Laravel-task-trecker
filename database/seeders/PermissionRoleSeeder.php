<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Permission\PermissionEnum;
use App\Enums\Role\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::query()->truncate();
        Permission::query()->truncate();

        $admin = Role::query()->create(['name' => RoleEnum::ADMIN->getValue()]);
        $user = Role::query()->create(['name' => RoleEnum::USER->getValue()]);

        $usersCrud = Permission::query()->create(['name' => PermissionEnum::USERS_CRUD->getValue()]);
        $tasksCrud = Permission::query()->create(['name' => PermissionEnum::TASKS_CRUD->getValue()]);

        $userManage = Permission::query()->create(['name' => PermissionEnum::USER_MANAGE->getValue()]);
        $tasksManage = Permission::query()->create(['name' => PermissionEnum::TASKS_MANAGE->getValue()]);

        $admin->givePermissionTo($usersCrud);
        $admin->givePermissionTo($tasksCrud);

        $user->givePermissionTo($userManage);
        $user->givePermissionTo($tasksManage);
    }
}
