<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/users') -> group(function(){
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/perfil',[UserController::class,'profile']);
        Route::get('/roles',[UserController::class,'getRoles']);
        Route::get('/all',[UserController::class,'getAllUsers']);
        Route::get('/active',[UserController::class,'getActiveUsers']);
        Route::get('/{id}',[UserController::class,'show']);
        Route::put('/{id}',[UserController::class,'update']);
        Route::delete('/{id}',[UserController::class,'delete']);
    });
});
