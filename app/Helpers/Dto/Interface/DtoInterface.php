<?php

namespace App\Helpers\Dto\Interface;

interface DtoInterface
{
    public function __construct(array $data);

    public function toArray(): array;
}
