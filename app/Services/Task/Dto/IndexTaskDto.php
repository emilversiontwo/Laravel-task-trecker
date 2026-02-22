<?php
declare(strict_types=1);

namespace App\Services\Task\Dto;

use App\Enums\Task\TaskStatusEnum;
use App\Helpers\Dto\Dto;

class IndexTaskDto extends Dto
{
    public ?string $title = null;

    public ?string $description = null;

    public ?TaskStatusEnum $status = null;

    public ?int $user_id = null;

    public ?int $per_page = null;

    public ?int $page = null;
}
