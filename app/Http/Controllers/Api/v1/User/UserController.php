<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\User;

use App\Enums\Role\RoleEnum;
use App\Exceptions\AppLogicException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\v1\User\StoreUserRequest;
use App\Http\Requests\Api\v1\User\UpdateUserRequest;
use App\Http\Resources\Api\v1\User\UserResource;
use App\Models\User;
use App\Services\User\Dto\UserIdDto;
use App\Services\User\Service\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class UserController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    )
    {
    }

    public function index(): JsonResponse
    {
        $users = $this->userService->index();

        return UserResource::collection($users)->response()->setStatusCode(ResponseCode::HTTP_OK);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $dto = $request->toDto();

        $user = $this->userService->store($dto);

        return UserResource::make($user)->response()->setStatusCode(ResponseCode::HTTP_CREATED);
    }

    /**
     * @throws AppLogicException
     */
    public function update(User $user, UpdateUserRequest $request): JsonResponse
    {
        if (
            $request->user()->id !== $user->id &&
            !$request->user()->hasRole(RoleEnum::ADMIN->getValue())
        ) {
            throw new AppLogicException('Insufficient permissions', ResponseCode::HTTP_FORBIDDEN);
        }

        $dto = $request->toDto($user->id);

        $user = $this->userService->update($dto);

        return UserResource::make($user)->response()->setStatusCode(ResponseCode::HTTP_OK);
    }

    /**
     * @throws AppLogicException
     */
    public function destroy(User $user, Request $request): Response
    {
        if (
            $request->user()->id !== $user->id &&
            !$request->user()->hasRole(RoleEnum::ADMIN->getValue())
        ) {
            throw new AppLogicException('Insufficient permissions', ResponseCode::HTTP_FORBIDDEN);
        }

        $dto = new UserIdDto([
            'user_id' => $user->id,
        ]);

        $this->userService->destroy($dto);

        return response()->noContent()->setStatusCode(ResponseCode::HTTP_NO_CONTENT);
    }

    /**
     * @throws AppLogicException
     */
    public function show(User $user, Request $request): JsonResponse
    {
        if (
            $request->user()->id !== $user->id &&
            !$request->user()->hasRole(RoleEnum::ADMIN->getValue())
        ) {
            throw new AppLogicException('Insufficient permissions', ResponseCode::HTTP_FORBIDDEN);
        }

        $dto = new UserIdDto([
            'user_id' => $user->id,
        ]);

        $user = $this->userService->show($dto);

        return UserResource::make($user)->response()->setStatusCode(ResponseCode::HTTP_OK);
    }

    public function current(Request $request)
    {
        $user = $request->user();

        return UserResource::make($user)->response()->setStatusCode(ResponseCode::HTTP_OK);
    }
}
