<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post("register",[UserController::class,"store"]);
Route::post("login", [UserController::class, "login"]);

Route::middleware('auth:api')->group(
    function () {

        Route::get("user", [UserController::class, "user"]);

    }
);
