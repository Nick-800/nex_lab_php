<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserTestController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

Route::post("register",[UserController::class,"store"]);
Route::post("login", [UserController::class, "login"]);

Route::middleware('auth:api')->group(
    function () {
        // User Routes
        Route::post('logout',[UserController::class,'logout'] );
        Route::get("user", [UserController::class, "user"]);

        // Test Routes
        Route::post("test", [TestController::class, "store"]);
        Route::get("test/{id}", [TestController::class, "show"]);
        Route::put("test/edit",[TestController::class,"update"]);
        Route::delete("test/delete/{id}",[TestController::class,"destroy"]);


        // User Test Routes
        Route::get("user/tests",[UserTestController::class, "index"]);
        Route::post("user/test/add",[UserTestController::class, "store"]);
        Route::get("user/test/{id}",[UserTestController::class, "show"]);
        Route::delete("user/test/delete/{id}",[UserTestController::class, "destroy"]);


    }
);
