<?php
declare(strict_types=1);

namespace App\Services\Auth\Service;

use App\Enums\Role\RoleEnum;
use App\Models\User;
use App\Services\Auth\Dto\LoginAuthDto;
use App\Services\Auth\Dto\LogoutAuthDto;
use App\Services\Auth\Dto\RegistrationAuthDto;
use App\Services\Auth\Dto\UserIdAuthDto;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{

    /**
     * Register new user and set role "user"
     * @param RegistrationAuthDto $dto
     * @return string
     */
    public function registration(RegistrationAuthDto $dto): string
    {
        $user = new User();

        $user->email = $dto->email;
        $user->password = Hash::make($dto->password);
        $user->name = $dto->name;

        $user->save();

        $user->assignRole(RoleEnum::USER->getValue());

        return $user->createToken($dto->token_name)->plainTextToken;
    }

    /**
     * Compare email, password, and token creation
     * @param LoginAuthDto $dto
     * @return string
     */
    public function login(LoginAuthDto $dto): string
    {
        $user = User::query()->where('email', $dto->email)->first();

        if (!$user || !Hash::check($dto->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
                'password' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user->createToken($dto->token_name)->plainTextToken;
    }

    /**
     * Delete a token by id or by the current token
     * @param LogoutAuthDto $dto
     * @return void
     */
    public function logout(LogoutAuthDto $dto): void
    {
        if ($dto->id) {
            $dto->user->tokens()->where('id', $dto->id)->firstOrFail()->delete();
        } else {
            $dto->user->currentAccessToken()->delete();
        }
    }

    /**
     * Get all token records
     * @param UserIdAuthDto $dto
     * @return Collection
     */
    public function getSessions(UserIdAuthDto $dto): Collection
    {
        $user = User::query()->findOrFail($dto->user_id);
        return $user->tokens()->get()->makeHidden('token');
    }

    /**
     * Delete all tokens
     * @param UserIdAuthDto $dto
     * @return void
     */
    public function logoutAll(UserIdAuthDto $dto): void
    {
        $user = User::query()->findOrFail($dto->user_id);
        $user->tokens()->delete();
    }
}
