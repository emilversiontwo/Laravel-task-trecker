<?php

namespace Tests\Feature\Http\Controllers\Api\v1\User;

use App\Enums\Role\RoleEnum;
use App\Models\Task;
use App\Models\User;
use Database\Seeders\PermissionRoleSeeder;
use Database\Seeders\TaskSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response as ResponseCode;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    public User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        $this->seed([
            PermissionRoleSeeder::class,
            UserSeeder::class,
        ]);

        $this->user = User::factory()->create();
        $this->user->assignRole(RoleEnum::USER->getValue());

        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole(RoleEnum::ADMIN->getValue());
    }

    #[Test]
    public function test_index_success()
    {
        $this->actingAs($this->adminUser);

        $response = $this->getJson(route('api.v1.users.index'));

        $response->assertStatus(ResponseCode::HTTP_OK);
    }

    #[Test]
    public function test_index_fail()
    {
        $this->actingAs($this->user);

        $response = $this->getJson(route('api.v1.users.index'));

        $response->assertStatus(ResponseCode::HTTP_FORBIDDEN);
    }

    #[Test]
    public function test_show_success()
    {
        $this->actingAs($this->adminUser);

        $response = $this->getJson(route('api.v1.users.show', $this->user->id));

        $response->assertStatus(ResponseCode::HTTP_OK);
    }

    #[Test]
    public function test_show_fail()
    {
        $this->actingAs($this->user);

        $response = $this->getJson(route('api.v1.users.show', $this->user->id));

        $response->assertStatus(ResponseCode::HTTP_FORBIDDEN);
    }

    #[Test]
    public function test_store_success()
    {
        $this->actingAs($this->adminUser);

        $request = [
            'name' => $this->user->name . 'updated',
            'email' => 'updated' . $this->user->email,
            'password' => $this->user->password,
            'role' => RoleEnum::USER->value,
        ];

        $response = $this->postJson(route('api.v1.users.store'), $request);

        $response->assertStatus(ResponseCode::HTTP_CREATED);
    }

    #[Test]
    public function test_store_fail()
    {
        $this->actingAs($this->user);

        $request = [
            'name' => $this->user->name . 'stored',
            'email' => 'stored' . $this->user->email,
            'password' => $this->user->password,
            'role' => RoleEnum::USER->value,
        ];

        $response = $this->postJson(route('api.v1.users.store'), $request);

        $response->assertStatus(ResponseCode::HTTP_FORBIDDEN);
    }

    #[Test]
    public function test_update_success()
    {
        $this->actingAs($this->adminUser);

        $request = [
            'name' => $this->user->name . 'updated',
            'email' => 'updated' . $this->user->email,
            'password' => $this->user->password,
            'role' => RoleEnum::USER->value,
        ];

        $response = $this->patchJson(route('api.v1.users.update', $this->user->id), $request);

        $response->assertStatus(ResponseCode::HTTP_OK);
    }

    #[Test]
    public function test_update_self_success()
    {
        $this->actingAs($this->user);

        $request = [
            'name' => $this->user->name . 'updated',
            'email' => 'updated' . $this->user->email,
            'password' => $this->user->password,
            'role' => RoleEnum::USER->value,
        ];

        $response = $this->patchJson(route('api.v1.users.update', $this->user->id), $request);

        $response->assertStatus(ResponseCode::HTTP_OK);
    }

    #[Test]
    public function test_update_admin_fail()
    {
        $this->actingAs($this->user);

        $request = [
            'name' => $this->user->name . 'updated',
            'email' => 'updated' . $this->user->email,
            'password' => $this->user->password,
            'role' => RoleEnum::USER->value,
        ];

        $response = $this->patchJson(route('api.v1.users.update', $this->adminUser->id), $request);

        $response->assertStatus(ResponseCode::HTTP_FORBIDDEN);
    }

    #[Test]
    public function test_destroy_success()
    {
        $this->actingAs($this->adminUser);

        $response = $this->deleteJson(route('api.v1.users.destroy', $this->user->id));

        $response->assertStatus(ResponseCode::HTTP_NO_CONTENT);
    }

    #[Test]
    public function test_destroy_fail()
    {
        $this->actingAs($this->user);

        $response = $this->deleteJson(route('api.v1.users.destroy', $this->user->id));

        $response->assertStatus(ResponseCode::HTTP_FORBIDDEN);
    }

    public function test_current_success()
    {
        $this->actingAs($this->user);

        $response = $this->getJson(route('api.v1.current'));

        $response->assertStatus(ResponseCode::HTTP_OK);
        $response->assertJson([
            'data' => [
                'id' => $this->user->id,
            ]
        ]);
    }
}
