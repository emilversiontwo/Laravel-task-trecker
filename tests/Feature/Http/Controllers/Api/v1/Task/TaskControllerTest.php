<?php

namespace Tests\Feature\Http\Controllers\Api\v1\Task;

use App\Enums\Role\RoleEnum;
use App\Http\Controllers\Api\v1\Task\TaskController;
use App\Models\Task;
use App\Models\User;
use Database\Seeders\PermissionRoleSeeder;
use Database\Seeders\TaskSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response as ResponseCode;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    public Task $task;

    public User $adminUser;

    public Task $adminTask;

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
            TaskSeeder::class,
        ]);

        $this->user = User::factory()->create();
        $this->user->assignRole(RoleEnum::USER->getValue());

        $this->task = Task::factory()->make();
        $this->task->user()->associate($this->user);
        $this->task->save();

        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole(RoleEnum::ADMIN->getValue());

        $this->adminTask = Task::factory()->make();
        $this->adminTask->user()->associate($this->adminUser);
        $this->adminTask->save();
    }

    #[Test]
    public function test_index_success()
    {
        $this->actingAs($this->user);

        $response = $this->getJson(route('api.v1.tasks.index'));

        $response->assertStatus(ResponseCode::HTTP_OK);
    }

    #[Test]
    public function test_index_with_property_success()
    {
        $this->actingAs($this->user);

        $request = [
            'title' => $this->task->title,
            'description' => $this->task->description,
            'status' => $this->task->status,
            'user_id' => $this->task->user_id,
        ];

        $response = $this->getJson(route('api.v1.tasks.index', $request));

        $response->assertStatus(ResponseCode::HTTP_OK);
        $response->assertJsonCount(1, 'data');
    }

    #[Test]
    public function test_index_fail()
    {
        $response = $this->getJson(route('api.v1.tasks.index'));

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    #[Test]
    public function test_show_success()
    {
        $this->actingAs($this->user);

        $response = $this->getJson(route('api.v1.tasks.show', $this->task->id));

        $response->assertStatus(ResponseCode::HTTP_OK);
        $response->assertJson([
            'data' => [
                'id' => $this->task->id,
            ],
        ]);
    }

    #[Test]
    public function test_show_fail()
    {
        $response = $this->getJson(route('api.v1.tasks.show', $this->task->id));

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    #[Test]
    public function test_store_success()
    {
        $this->actingAs($this->user);

        $request = [
            'title' => $this->task->title,
            'description' => $this->task->description,
            'status' => $this->task->status,
        ];

        $response = $this->postJson(route('api.v1.tasks.store'), $request);

        $response->assertStatus(ResponseCode::HTTP_CREATED);
    }

    #[Test]
    public function test_store_fail()
    {
        $request = [
            'title' => $this->task->title,
            'description' => $this->task->description,
            'status' => $this->task->status,
        ];

        $response = $this->postJson(route('api.v1.tasks.store'), $request);

        $response->assertStatus(ResponseCode::HTTP_UNAUTHORIZED);
    }

    #[Test]
    public function test_update_success()
    {
        $this->actingAs($this->user);

        $request = [
            'title' => $this->task->title . ' updated',
            'description' => $this->task->description . ' updated',
            'status' => $this->task->status,
        ];

        $response = $this->patchJson(route('api.v1.tasks.update', $this->task->id), $request);

        $response->assertStatus(ResponseCode::HTTP_OK);
    }

    #[Test]
    public function test_update_fail()
    {
        $this->actingAs($this->user);

        $request = [
            'title' => $this->task->title . ' updated',
            'description' => $this->task->description . ' updated',
            'status' => $this->task->status,
        ];

        $response = $this->patchJson(route('api.v1.tasks.update', $this->adminTask->id), $request);

        $response->assertStatus(ResponseCode::HTTP_FORBIDDEN);
    }

    #[Test]
    public function test_destroy_success()
    {
        $this->actingAs($this->user);

        $response = $this->deleteJson(route('api.v1.tasks.destroy', $this->task->id));

        $response->assertStatus(ResponseCode::HTTP_NO_CONTENT);
    }

    #[Test]
    public function test_destroy_fail()
    {
        $this->actingAs($this->user);

        $response = $this->deleteJson(route('api.v1.tasks.destroy', $this->adminTask->id));

        $response->assertStatus(ResponseCode::HTTP_FORBIDDEN);
    }
}
