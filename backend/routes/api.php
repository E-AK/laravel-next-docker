<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\Index;

Route::post('signin', Index::class);
