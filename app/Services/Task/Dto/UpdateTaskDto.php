<?php
declare(strict_types=1);

namespace App\Services\Task\Dto;

use App\Enums\Task\TaskStatusEnum;

class UpdateTaskDto extends TaskIdDto
{
    public ?string $title = null;

    public ?string $description = null;

    public ?TaskStatusEnum $status = null;

    public ?int $user_id = null;
}
