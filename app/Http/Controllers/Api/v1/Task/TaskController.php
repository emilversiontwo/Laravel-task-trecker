<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Task;

use App\Enums\Permission\PermissionEnum;
use App\Exceptions\AppLogicException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Task\IndexTaskRequest;
use App\Http\Requests\Api\v1\Task\StoreTaskRequest;
use App\Http\Requests\Api\v1\Task\UpdateTaskRequest;
use App\Http\Resources\Api\v1\Task\TaskResource;
use App\Models\Task;
use App\Services\Task\Dto\TaskIdDto;
use App\Services\Task\Service\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService
    )
    {
    }

    public function index(IndexTaskRequest $request)
    {
        $dto = $request->toDto();

        $tasks = $this->taskService->index($dto);

        return TaskResource::collection($tasks)->response()->setStatusCode(ResponseCode::HTTP_OK);
    }

    public function store(StoreTaskRequest $request)
    {
        $dto = $request->toDto();

        $task = $this->taskService->store($dto);

        return TaskResource::make($task)->response()->setStatusCode(ResponseCode::HTTP_CREATED);
    }

    /**
     * @param Task $task
     * @return JsonResponse
     */
    public function show(Task $task)
    {
        $dto = new TaskIdDto([
            'task_id' => $task->id,
        ]);

        $task = $this->taskService->show($dto);

        return TaskResource::make($task)->response()->setStatusCode(ResponseCode::HTTP_OK);
    }

    /**
     * @throws AppLogicException
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        if (
            $request->user()->id !== $task->user_id &&
            !$request->user()->hasPermissionTo(PermissionEnum::TASKS_CRUD)
        ) {
            throw new AppLogicException('Insufficient permissions', ResponseCode::HTTP_FORBIDDEN);
        }

        $dto = $request->toDto($task->id);

        $task = $this->taskService->update($dto);

        return TaskResource::make($task)->response()->setStatusCode(ResponseCode::HTTP_OK);
    }

    /**
     * @throws AppLogicException
     */
    public function destroy(Task $task, Request $request)
    {
        if (
            $request->user()->id !== $task->user_id &&
            !$request->user()->hasPermissionTo(PermissionEnum::TASKS_CRUD)
        ) {
            throw new AppLogicException('Insufficient permissions', ResponseCode::HTTP_FORBIDDEN);
        }

        $dto = new TaskIdDto([
            'task_id' => $task->id,
        ]);

        $this->taskService->destroy($dto);

        return response()->noContent()->setStatusCode(ResponseCode::HTTP_NO_CONTENT);
    }
}
