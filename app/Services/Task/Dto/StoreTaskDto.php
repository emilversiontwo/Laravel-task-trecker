<?php
declare(strict_types=1);

namespace App\Services\Task\Dto;

use App\Enums\Task\TaskStatusEnum;
use App\Helpers\Dto\Dto;

class StoreTaskDto extends Dto
{
    public string $title;

    public string $description;

    public TaskStatusEnum $status = TaskStatusEnum::TODO;

    public int $user_id;
}
