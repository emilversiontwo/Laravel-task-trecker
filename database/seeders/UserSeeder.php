<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Role\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->truncate();

        $admin = User::factory()->create([
            'name' => 'administrator',
            'email' => 'admin@example.com',
        ]);
        $admin->assignRole(RoleEnum::ADMIN->getValue());

        $user = User::factory()->create([
            'name' => 'user',
            'email' => 'user@example.com',
        ]);
        $user->assignRole(RoleEnum::USER->getValue());
    }
}
