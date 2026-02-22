<?php

namespace App\Services\Auth\Dto;

use App\Helpers\Dto\Dto;
use App\Models\User;

class LogoutAllAuthDto extends Dto
{
    public User $user;
}
