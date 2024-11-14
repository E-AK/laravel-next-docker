<?php

use App\Http\Controllers\Api\Auth\Signup;
use App\Http\Controllers\Api\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\Signin;

Route::prefix('auth')->group(function () {
    Route::post('signup', Signup::class);
    Route::post('signin', Signin::class);
});

Route::middleware(['auth:api'])->group(function () {
    Route::prefix('user')->group(function () {
        Route::get('/me', User\Get::class);
    });
});
