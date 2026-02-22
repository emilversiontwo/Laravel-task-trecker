<?php
declare(strict_types=1);

namespace App\Services\Auth\Dto;

use App\Helpers\Dto\Dto;
use App\Models\User;

class UserIdAuthDto extends Dto
{
    public int $user_id;
}
