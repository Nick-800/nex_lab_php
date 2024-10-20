<?php

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserTestController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserResultController;
use Illuminate\Support\Facades\Route;

Route::post("register",[UserController::class,"store"]);
Route::post("login", [UserController::class, "login"]);

Route::middleware('auth:api')->group(
    function () {
        // User Routes
        Route::post('logout',[UserController::class,'logout'] );
        Route::get("user", [UserController::class, "user"]);

        // Test Routes
        Route::post("test/add", [TestController::class, "store"]);
        Route::get("tests", [TestController::class, "index"]);
        Route::get("test/{id}", [TestController::class, "show"]);
        Route::put("test/edit",[TestController::class,"update"]);
        Route::delete("test/delete/{id}",[TestController::class,"destroy"]);


        // User Test Routes
        Route::get("user/tests",[UserTestController::class, "index"]);
        Route::post("user/test/add",[UserTestController::class, "store"]);
        Route::get("user/test/{id}",[UserTestController::class, "show"]);
        Route::delete("user/test/delete/{id}",[UserTestController::class, "destroy"]);


        // User Result Routes
        Route::post('user/result/add', [UserResultController::class, 'store']);
        Route::get('user/results', [UserResultController::class, 'index']);
        Route::get('user/result/{id}', [UserResultController::class, 'show']);
        Route::delete('user/result/delete/{id}', [UserResultController::class, 'destroy']);
        Route::get('user/result/download/{id}', [UserResultController::class, 'download']);
        Route::put('user/result/update/{id}', [UserResultController::class, 'update']);




    }
);
