<?php
declare(strict_types=1);

namespace App\Services\Task\Service;

use App\Models\Task;
use App\Services\Task\Dto\IndexTaskDto;
use App\Services\Task\Dto\StoreTaskDto;
use App\Services\Task\Dto\TaskIdDto;
use App\Services\Task\Dto\UpdateTaskDto;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{
    public function index(IndexTaskDto $dto): LengthAwarePaginator
    {
        $tasks = Task::query();

        $tasks->when($dto->title, function ($query) use ($dto) {
            return $query->where('title', 'LIKE', '%' . $dto->title . '%');
        });

        $tasks->when($dto->description, function ($query) use ($dto) {
            return $query->where('description', 'LIKE', '%' . $dto->description . '%');
        });

        $tasks->when($dto->status, function ($query) use ($dto) {
            return $query->where('status', $dto->status->getValue());
        });

        $tasks->when($dto->user_id, function ($query) use ($dto) {
            return $query->where('user_id', $dto->user_id);
        });

        return $tasks->paginate(perPage: $dto->per_page ?? 10, page: $dto->page ?? 1);
    }

    public function store(StoreTaskDto $dto): Task
    {
        $task = new Task();

        $task->title = $dto->title;
        $task->description = $dto->description;
        $task->status = $dto->status->getValue();

        $task->user_id = $dto->user_id;

        $task->save();

        return $task;
    }

    public function update(UpdateTaskDto $dto): Task
    {
        $task = Task::query()->findOrFail($dto->task_id);

        if ($dto->title) {
            $task->title = $dto->title;
        }

        if ($dto->description) {
            $task->description = $dto->description;
        }

        if ($dto->status) {
            $task->status = $dto->status->getValue();
        }

        if ($dto->user_id) {
            $task->user_id = $dto->user_id;
        }

        $task->save();

        return $task;
    }

    public function destroy(TaskIdDto $dto): void
    {
        $task = Task::query()->findOrFail($dto->task_id);
        $task->delete();
    }

    public function show(TaskIdDto $dto): Task
    {
        return Task::query()->findOrFail($dto->task_id);
    }
}
