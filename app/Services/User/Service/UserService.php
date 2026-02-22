<?php
declare(strict_types=1);

namespace App\Services\User\Service;

use App\Models\User;
use App\Services\User\Dto\StoreUserDto;
use App\Services\User\Dto\UpdateUserDto;
use App\Services\User\Dto\UserIdDto;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function index(): Collection
    {
        return User::query()->get();
    }

    public function store(StoreUserDto $dto): User
    {
        $user = new User();

        $user->name = $dto->name;
        $user->email = $dto->email;
        $user->password = Hash::make($dto->password);

        $user->save();

        $user->assignRole($dto->role->getValue());

        return $user;
    }

    public function update(UpdateUserDto $dto): User
    {
        $user = User::query()->findOrFail($dto->user_id);

        if ($dto->name){
            $user->name = $dto->name;
        }

        if ($dto->email){
            $user->email = $dto->email;
        }

        if (!empty($dto->password)) {
            $user->password = Hash::make($dto->password);
        }

        $user->save();

        if (!empty($dto->role)) {
            $user->roles()->detach();
            $user->assignRole($dto->role->getValue());
        }

        return $user;
    }

    public function destroy(UserIdDto $dto): void
    {
        $user = User::query()->findOrFail($dto->user_id);
        $user->delete();
    }

    public function show(UserIdDto $dto): User
    {
        return User::query()->findOrFail($dto->user_id);
    }
}
