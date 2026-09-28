<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require __DIR__.'/api/auth.php';
require __DIR__.'/api/user.php';
require __DIR__.'/api/libro.php';
require __DIR__.'/api/report.php';

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
