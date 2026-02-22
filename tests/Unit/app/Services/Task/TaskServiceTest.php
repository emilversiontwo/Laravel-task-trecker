<?php

namespace Tests\Unit\app\Services\Task;

use App\Enums\Role\RoleEnum;
use App\Enums\Task\TaskStatusEnum;
use App\Models\Task;
use App\Models\User;
use App\Services\Task\Dto\IndexTaskDto;
use App\Services\Task\Dto\StoreTaskDto;
use App\Services\Task\Dto\TaskIdDto;
use App\Services\Task\Dto\UpdateTaskDto;
use App\Services\Task\Service\TaskService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    public Task $task;

    public User $adminUser;

    public Task $adminTask;

    public string $table;

    #[Test]
    public function test_index()
    {
        $service = app(TaskService::class);

        $dto = new IndexTaskDto([
            'title' => $this->task->title,
            'description' => $this->task->description,
            'status' => TaskStatusEnum::from($this->task->status),
            'user_id' => $this->user->id,
        ]);

        $result = $service->index($dto);

        $this->assertCount(1, $result->all());
    }

    #[Test]
    public function test_store()
    {
        $service = app(TaskService::class);

        $dto = new StoreTaskDto([
            'title' => $this->task->title,
            'description' => $this->task->description,
            'status' => TaskStatusEnum::from($this->task->status),
            'user_id' => $this->user->id,
        ]);

        $result = $service->store($dto);

        $this->assertDatabaseHas($this->table, $result->getAttributes());
    }

    #[Test]
    public function test_update()
    {
        $service = app(TaskService::class);

        $dto = new UpdateTaskDto([
            'title' => $this->task->title . 'updated',
            'description' => $this->task->description . 'updated',
            'status' => TaskStatusEnum::COMPLETE,
            'user_id' => $this->user->id,
            'task_id' => $this->task->id,
        ]);

        $result = $service->update($dto);

        $this->assertDatabaseHas($this->table, $result->getAttributes());
    }

    #[Test]
    public function test_destroy()
    {
        $service = app(TaskService::class);

        $dto = new TaskIdDto([
            'task_id' => $this->task->id,
        ]);

        $service->destroy($dto);

        $this->assertDatabaseMissing($this->table, ['id' => $this->task->id]);
    }

    #[Test]
    public function test_show()
    {
        $service = app(TaskService::class);

        $dto = new TaskIdDto([
            'task_id' => $this->task->id,
        ]);

        $service->show($dto);

        $this->assertDatabaseHas($this->table, ['id' => $this->task->id]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);

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

        $this->table = Task::getModel()->getTable();
    }
}
