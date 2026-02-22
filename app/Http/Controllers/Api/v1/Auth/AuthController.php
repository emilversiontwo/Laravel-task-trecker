<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\Auth\LoginAuthRequest;
use App\Http\Requests\Api\v1\Auth\LogoutAuthRequest;
use App\Http\Requests\Api\v1\Auth\RegistrationAuthRequest;
use App\Http\Resources\Api\v1\Auth\AuthResource;
use App\Services\Auth\Dto\GetSessionsAuthDto;
use App\Services\Auth\Dto\LoginAuthDto;
use App\Services\Auth\Dto\LogoutAllAuthDto;
use App\Services\Auth\Dto\LogoutAuthDto;
use App\Services\Auth\Dto\RegistrationAuthDto;
use App\Services\Auth\Service\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    )
    {}

    public function registration(RegistrationAuthRequest $request)
    {
        $data = $request->validated();
        $dto = new RegistrationAuthDto([
            ...$data,
            'token_name' => $request->device_name ?? $request->userAgent() ?? Str::random(20),
        ]);

        $token = $this->authService->registration($dto);

        return response()->json(data: [
            'type' => 'Bearer',
            'token' => $token,
        ])->setStatusCode(ResponseCode::HTTP_CREATED);
    }

    public function login(LoginAuthRequest $request): JsonResponse
    {
        $data = $request->validated();
        $dto = new LoginAuthDto([
            ...$data,
            'token_name' => $request->device_name ?? $request->userAgent() ?? Str::random(20),
        ]);

        $token = $this->authService->login($dto);

        return response()->json(data: [
            'type' => 'Bearer',
            'token' => $token,
        ])->setStatusCode(ResponseCode::HTTP_CREATED);

    }

    public function logout(LogoutAuthRequest $request): Response
    {
        $data = $request->validated();
        $dto = new LogoutAuthDto([
            ...$data,
            'user' => $request->user(),
        ]);

        $this->authService->logout($dto);

        return response()->noContent()->setStatusCode(ResponseCode::HTTP_NO_CONTENT);

    }

    public function logoutAll(Request $request)
    {
        $dto = new LogoutAllAuthDto([
            'user' => $request->user(),
        ]);

        $this->authService->logoutAll($dto);

        return response()->noContent()->setStatusCode(ResponseCode::HTTP_NO_CONTENT);
    }

    public function getSessions(Request $request)
    {
        $dto = new GetSessionsAuthDto([
            'user' => $request->user(),
        ]);

        $tokens = $this->authService->getSessions($dto);

        return AuthResource::collection($tokens)->response()->setStatusCode(ResponseCode::HTTP_OK);
    }
}
