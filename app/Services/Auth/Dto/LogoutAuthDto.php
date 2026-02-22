<?php
declare(strict_types=1);

namespace App\Services\Auth\Dto;

use App\Helpers\Dto\Dto;
use App\Models\User;

class LogoutAuthDto extends Dto
{
    public ?int $id = null;
    public ?string $token = null;

    public User $user;
}
