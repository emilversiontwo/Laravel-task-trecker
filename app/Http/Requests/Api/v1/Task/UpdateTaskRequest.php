<?php
declare(strict_types=1);

namespace App\Http\Requests\Api\v1\Task;

use App\Enums\Task\TaskStatusEnum;
use App\Services\Task\Dto\UpdateTaskDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'status' => ['sometimes', Rule::enum(TaskStatusEnum::class)],
            'user_id' => ['sometimes', 'integer', 'exists:users'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function toDto(int $task_id): UpdateTaskDto
    {
        $data = $this->validated();

        $taskStatusEnum = array_key_exists('status', $data) ? TaskStatusEnum::from($data['status']) : null;

        return new UpdateTaskDto([
            ...$data,
            'status' => $taskStatusEnum,
            'task_id' => $task_id,
        ]);
    }
}
