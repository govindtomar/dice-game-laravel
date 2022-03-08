<?php


Route::group(['middleware' => ['auth:sanctum']], function () {
    // Route::get('user', [UserController::class, 'index']);
});