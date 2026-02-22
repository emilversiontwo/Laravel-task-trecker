<?php
declare(strict_types=1);

namespace App\Http\Requests\Api\v1\Task;

use App\Enums\Role\RoleEnum;
use App\Enums\Task\TaskStatusEnum;
use App\Services\Task\Dto\IndexTaskDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexTaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'status' => ['sometimes', Rule::enum(TaskStatusEnum::class)],
            'user_id' => ['sometimes', 'integer', 'exists:users,id'],
            'per_page' => ['sometimes', 'integer', 'min:1'],
            'page' => ['sometimes', 'integer', 'min:1'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function toDto(): IndexTaskDto
    {
        $data = $this->validated();

        $taskStatusEnum = array_key_exists('status', $data) ? TaskStatusEnum::from($data['status']) : null;

        return new IndexTaskDto([
            ...$data,
            'user_id' => intval($data['user_id'] ?? null),
            'per_page' => intval($data['per_page'] ?? 10),
            'page' => intval($data['page'] ?? 1),
            'status' => $taskStatusEnum,
        ]);
    }
}
