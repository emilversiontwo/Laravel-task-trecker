<?php

namespace App\Services\Auth\Service;

use App\Http\Requests\Api\v1\Auth\LoginAuthRequest;
use App\Http\Requests\Api\v1\Auth\LogoutAuthRequest;
use App\Http\Requests\Api\v1\Auth\RegistrationAuthRequest;
use App\Models\User;
use App\Services\Auth\Dto\GetSessionsAuthDto;
use App\Services\Auth\Dto\LoginAuthDto;
use App\Services\Auth\Dto\LogoutAllAuthDto;
use App\Services\Auth\Dto\LogoutAuthDto;
use App\Services\Auth\Dto\RegistrationAuthDto;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

        $user->assignRole('user');

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
        if ($dto->id){
            $dto->user->tokens()->where('id', $dto->id)->firstOrFail()->delete();
        } else {
            $dto->user->currentAccessToken()->delete();
        }
    }

    /**
     * Get all token records
     * @param GetSessionsAuthDto $dto
     * @return Collection
     */
    public function getSessions(GetSessionsAuthDto $dto): Collection
    {
        return $dto->user->tokens()->get()->makeHidden('token');
    }

    /**
     * Delete all tokens
     * @param LogoutAllAuthDto $dto
     * @return void
     */
    public function logoutAll(LogoutAllAuthDto $dto): void
    {
        $dto->user->tokens()->delete();
    }
}
