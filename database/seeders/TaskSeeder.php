<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        Task::query()->truncate();

        for ($i = 1; $i <= 7; $i++) {
            $task = Task::factory()->make();
            $task->user()->associate(User::query()->inRandomOrder()->first());
            $task->save();
        }
    }
}
