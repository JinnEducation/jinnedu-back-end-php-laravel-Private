<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\CateqBlogController;
use App\Http\Controllers\Api\AccessTokensController;



Route::apiResource('blog', BlogController::class);
// Route::get('/blogs/{blog}', [BlogController::class, 'show']);
// Route::put('blog/{id}', [BlogController::class, 'update']);
// Route::patch('blog/{id}', [BlogController::class, 'update']);
Route::apiResource('cateqblog', CateqBlogController::class);
Route::apiResource('slider', SliderController::class);


Route::post('auth/access-tokens', [AccessTokensController::class, 'store'])
    ->middleware('guest:sanctum');
Route::delete('auth/access-tokens/{token?}', [AccessTokensController::class, 'destroy'])
    ->middleware('auth:sanctum');






