<?php
declare(strict_types=1);

namespace App\Http\Requests\Api\v1\Task;

use App\Enums\Task\TaskStatusEnum;
use App\Services\Task\Dto\StoreTaskDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'status' => ['nullable', Rule::enum(TaskStatusEnum::class)],
            'user_id' => ['nullable', 'exists:users,id'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function toDto(): StoreTaskDto
    {
        $data = $this->validated();

        $taskStatusEnum = array_key_exists('status', $data) ? TaskStatusEnum::from($data['status']) : null;

        return new StoreTaskDto([
            ...$data,
            'user_id' => intval($data['user_id'] ?? $this->user()->id),
            'status' => $taskStatusEnum ?? TaskStatusEnum::TODO,
        ]);
    }
}
