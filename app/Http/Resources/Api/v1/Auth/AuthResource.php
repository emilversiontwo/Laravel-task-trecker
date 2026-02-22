<?php

namespace App\Http\Resources\Api\v1\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * @mixin PersonalAccessToken
 */
class AuthResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'last-activity' => $this->last_used_at,
            'created-at' => $this->created_at,
        ];
    }
}
