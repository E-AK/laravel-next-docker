<?php

use App\Http\Controllers\Api\Auth\Signup;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\Signin;

Route::prefix('auth')->group(function () {
    Route::post('signup', Signup::class);
    Route::post('signin', Signin::class);
});
