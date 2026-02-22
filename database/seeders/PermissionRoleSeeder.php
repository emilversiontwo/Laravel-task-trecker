<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::query()->truncate();
        Permission::query()->truncate();

        $admin = Role::query()->create(['name' => 'admin']);
        $user = Role::query()->create(['name' => 'user']);

        $usersCrud = Permission::query()->create(['name' => 'users.crud']);
        $tasksCrud = Permission::query()->create(['name' => 'tasks.crud']);

        $userManage = Permission::query()->create(['name' => 'user.manage']);
        $tasksManage = Permission::query()->create(['name' => 'tasks.manage']);

        $admin->givePermissionTo($usersCrud);
        $admin->givePermissionTo($tasksCrud);

        $user->givePermissionTo($userManage);
        $user->givePermissionTo($tasksManage);
    }
}
