<?php
declare(strict_types=1);

namespace App\Helpers\Dto;

use App\Helpers\Dto\Interface\DtoInterface;

class Dto implements DtoInterface
{
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;

                continue;
            }
            $camelCase = $this->snakeToCamelCase($key);
            if (property_exists($this, $camelCase)) {
                $this->{$camelCase} = $value;
            }
        }
    }

    private function snakeToCamelCase(string $key): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $key))));
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
