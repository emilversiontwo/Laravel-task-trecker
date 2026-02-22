<?php

use App\Http\Controllers\Api\v1\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

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

});
