<?php
declare(strict_types=1);

namespace App\Enums\Task;

enum TaskStatusEnum: string
{
    case TODO = 'todo';

    case WORK = 'work';

    case TEST = 'test';

    case COMPLETE = 'complete';

    public function getValue(): string
    {
        return $this->value;
    }

    public static function values(): array
    {
        return array_map(fn (TaskStatusEnum $value) => $value->value, self::cases());
    }
}
