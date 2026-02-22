<?php
declare(strict_types=1);

namespace App\Services\Auth\Dto;

use App\Helpers\Dto\Dto;

class RegistrationAuthDto extends Dto
{
    public string $email;

    public string $password;

    public string $name = "";

    public string $token_name;
}
