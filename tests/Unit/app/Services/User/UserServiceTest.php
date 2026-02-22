<?php

namespace Tests\Unit\app\Services\User;

use App\Enums\Role\RoleEnum;
use App\Models\User;
use App\Services\User\Dto\StoreUserDto;
use App\Services\User\Dto\UpdateUserDto;
use App\Services\User\Dto\UserIdDto;
use App\Services\User\Service\UserService;
use Database\Seeders\PermissionRoleSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    public User $adminUser;

    public string $table;

    #[Test]
    public function test_index()
    {
        $service = app(UserService::class);

        $result = $service->index();

        $this->assertDatabaseHas($result->all());
    }

    #[Test]
    public function test_store()
    {
        $service = app(UserService::class);

        $dto = new StoreUserDto([
            'name' => $this->user->name . 'stored',
            'email' => 'stored' . $this->user->email,
            'password' => 'password',
            'role' => RoleEnum::USER,
        ]);

        $result = $service->store($dto);

        $this->assertDatabaseHas($this->table, $result->getAttributes());
    }

    #[Test]
    public function test_update()
    {
        $service = app(UserService::class);

        $dto = new UpdateUserDto([
            'name' => $this->user->name . 'stored',
            'email' => 'stored' . $this->user->email,
            'password' => 'password',
            'role' => RoleEnum::ADMIN,
            'user_id' => $this->user->id,
        ]);

        $result = $service->update($dto);

        $this->assertDatabaseHas($this->table, $result->getAttributes());
    }

    #[Test]
    public function test_show()
    {
        $this->refreshDatabase();
        $service = app(UserService::class);

        $dto = new UserIdDto([
            'user_id' => $this->user->id,
        ]);

        $service->show($dto);

        $this->assertDatabaseHas($this->table, ['id' => $this->user->id]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            PermissionRoleSeeder::class,
            UserSeeder::class,
        ]);

        $this->user = User::factory()->create();
        $this->user->assignRole(RoleEnum::USER->getValue());

        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole(RoleEnum::ADMIN->getValue());

        $this->table = User::getModel()->getTable();
    }
}
