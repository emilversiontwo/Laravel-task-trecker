<?php

use App\Enums\Permission\PermissionEnum;
use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\User\UserController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middleware\PermissionMiddleware;

Route::group(['prefix' => 'v1'], function () {

    Route::post('/register', [AuthController::class, 'registration'])
        ->name('api.v1.register');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('api.v1.login');

    Route::group(['middleware' => 'auth:sanctum'], function () {

        Route::post('/logout-all', [AuthController::class, 'logoutAll'])
            ->name('api.v1.logout-all');

        Route::post('/logout', [AuthController::class, 'logout'])
            ->name('api.v1.logout');

        route::get('/sessions', [AuthController::class, 'getSessions'])
            ->name('api.v1.sessions');
    });

    Route::group([
        'prefix' => 'users',
        'middleware' => 'auth:sanctum',
    ], function () {
        Route::group([
            'middleware' => PermissionMiddleware::using(PermissionEnum::USERS_CRUD->getValue())
        ], function () {
            Route::get('/', [UserController::class, 'index'])
                ->name('api.v1.users.index');
            Route::get('/{user}', [UserController::class, 'show'])
                ->name('api.v1.users.show');
            Route::post('/', [UserController::class, 'store'])
                ->name('api.v1.users.store');
            Route::delete('/{user}', [UserController::class, 'destroy'])
                ->name('api.v1.users.destroy');
        });
        Route::patch('/{user}', [UserController::class, 'update'])
            ->name('api.v1.users.update')
            ->middleware(PermissionMiddleware::using([
                PermissionEnum::USER_MANAGE->getValue(),
                PermissionEnum::USERS_CRUD->getValue()
            ]));
    });

    Route::get('/current', [UserController::class, 'current'])
        ->name('api.v1.current')
        ->middleware([
            'auth:sanctum',
        ]);
});
