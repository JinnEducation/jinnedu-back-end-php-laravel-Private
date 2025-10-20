<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogController;



Route::apiResource('blog', BlogController::class);
Route::get('/blogs/{blog}', [BlogController::class, 'show']);






