<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::apiResource('api/v1/posts', \App\Http\Controllers\PostController::class);
Route::apiResource('api/v1/categories', \App\Http\Controllers\CategoryController::class);
Route::apiResource('api/v1/tags', \App\Http\Controllers\TagController::class);
